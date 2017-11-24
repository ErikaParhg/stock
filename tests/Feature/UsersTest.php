<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\User;

class UsersTest extends TestCase
{
    public function test_user_log_in()
    {
        Artisan::call('migrate');

        $password = bcrypt('senha');

               User::create([
            'name' => 'Gigi',
            'email' => 'gigi@email.com',
            'permission' => 0,
            'password' => $password
        ]);

        $this->assertDatabaseHas('users', ['email' => 'gigi@email.com']);

        Session::start();
        $response = $this->withExceptionHandling()->call('POST', '/login', [
            'email' => 'gigi@email.com',
            'password' => 'senha',
            '_token' => csrf_token()
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('/home');
    }

    public function test_user_without_registration_not_login()
    {
        Artisan::call('migrate');

        Session::start();
        $response = $this->withExceptionHandling()->call('POST', '/login', [
            'email' => 'sos@email.com',
            'password' => 'sos123',
            '_token' => csrf_token()
        ]);

        $this->assertDatabaseMissing('users', ['email' => 'sos@email.com']);

        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('/');
    }

    public function test_users_without_registration_not_register_users()
    {
        Artisan::call('migrate');

        $response = $this->withExceptionHandling()->call('POST', '/user/register', [
            'name' => 'name',
            'email' => 'user@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'permission' => 0,
            '_token' => csrf_token()
        ]);

        $this->assertDatabaseMissing('users', ['email' => 'user@email.com']);      
    }  

    public function test_unregistered_users_not_insert_products()
    {
        Artisan::call('migrate');

        $supplier = factory('App\Supplier')->create();
        $response = $this->withExceptionHandling()->call('POST', '/product/insert', [
            'supplier' => ''.$supplier->id,
            'name'=>'Coragem',
            'description' => 'esta perdida',
            'cost' => '20',
            'quantity' => '100',
            '_token' => csrf_token()
        ]);
        $response->assertStatus(403);

        $this->assertDatabaseMissing('products', 
            [
            'supplier_id' => ''.$supplier->id,
            'name' => 'Coragem',
            'description' => 'esta perdida',
            'cost' => '20.0',
            'quantity' => '100',          
        ]);
    }

    public function test_unregistered_users_dont_insert_suppliers()
    {
        Artisan::call('migrate');
        
        $response = $this->withExceptionHandling()->call('POST','/supplier/insert', [
            'name' => 'Congate Ltda',
            'cnpj' => 1235401347732,
            'address' => 'Dutra, 52',
            '_token' => csrf_token()
        ]);
        $response->assertStatus(403);
      
        $this->assertDatabaseMissing('suppliers', ['name' => 'Congate Ltda', 'cnpj' => 1235401347732, 'address' => 'Dutra, 52']);
    }
}