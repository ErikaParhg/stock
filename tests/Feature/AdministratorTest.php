<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\User;

class AdministratorTest extends TestCase
{
 
    public function test_admin_sees_total_stock()
    {
        Artisan::call('migrate');

        $password = bcrypt('password');
        $usuarioA = User::create([
            'name' => 'Abgaiu',
            'email' => 'Abgaiu@email.com',
            'permission' => 1,
            'password' => $password
        ]);

        $usuarioB = User::create([
            'name' => 'Soraia',
            'email' => 'Soraia@email.com',
            'permission' => 0,
            'status' => 'Active',
            'password' => $password
        ]);

        $supplier = factory('App\Supplier')->create();
            for ($i = 0; $i < 5; $i++) { 
                $products[] = factory('App\Product')->create();
            }

        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);

        $response = $this->withExceptionHandling()->actingAs($usuarioA)->call('GET', '/dashboard');
        $response->assertStatus(200);

        $total = 0;
        foreach ($products as $product) {
            $response->assertSee($product->name);
            $response->assertSee(''.($product->cost * $product->quantity));
            $response->assertSee(''.$product->quantity);
            $total += ($product->custo * $product->quantity);
        }
        $response->assertSee(''.$total);
    } 

    public function test_administrator_access_user_management_page()
    {
        Artisan::call('migrate');

        $password = bcrypt('password');
        $userA = User::create([
            'name' => 'Nostradamus',
            'email' => 'nostradamus@email.com',
            'permission' => 1,
            'password' => $password
        ]);

        $userB = User::create([
            'name' => 'Charles Chaplin',
            'email' => 'c_chaplin@email.com',
            'permission' => 0,
            'password' => $password
        ]);

        $this->assertDatabaseHas('users', ['email' => 'nostradamus@email.com']);
        $this->assertDatabaseHas('users', ['email' => 'c_chaplin@email.com']);

        $response = $this->actingAs($userA)->call('GET', '/user');
        $response->assertStatus(200);
    }

    public function test_admin_register_user()
    {
        Artisan::call('migrate');

        $password = bcrypt('password');
        $user = User::create([
            'name' => 'Maria Nina',
            'email' => 'maria_nina@email.com',
            'permission' => 1,
            'password' => $password
        ]);
 
        $this->assertDatabaseHas('users', ['email' => 'maria_nina@email.com']);

        $response = $this->withExceptionHandling()->actingAs($user)->call('POST', '/user/register', [
            'name' => 'Bob Brisa',
            'email' => 'bob_brisa@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'permission' => 0,
            '_token' => csrf_token()
        ]);

        $this->assertDatabaseHas('users', ['email' => 'bob_brisa@email.com']);
    }

    public function test_administrator_list_users()
    {
        Artisan::call('migrate');

        $password = bcrypt('password');
        $userA = User::create([
            'name' => 'Consuelo',
            'email' => 'consuelo@email.com',
            'permission' => 1,
            'password' => $password
        ]);

        $userB = User::create([
            'name' => 'Astolfo',
            'email' => 'astolfo@email.com',
            'permission' => 0,
            'password' => $password
        ]);

        for ($i = 0; $i < 5; $i++) { 
            $users[] = factory('App\User')->create();
        }

        $response = $this->withExceptionHandling()->actingAs($userA)->call('GET', '/user');
        $response->assertStatus(200);

        foreach ($users as $user) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
        }
    }


     public function test_administrators_edit_users()
     {
        Artisan::call('migrate');

        $password = bcrypt('password');
        $userA = User::create([
            'name' => 'Edmundo',
            'email' => 'edmundo@email.com',
            'permission' => 1,
            'password' => $password
        ]);

        $userB = User::create([
            'name' => 'Raimunda',
            'email' => 'raimunda@email.com',
            'permission' => 0,
            'password' => $password
        ]);
 
        $this->assertDatabaseHas('users', ['email' => 'edmundo@email.com']);
        $this->assertDatabaseHas('users', ['email' => 'raimunda@email.com']);
        $this->assertDatabaseMissing('users', ['name' => 'brisa@email.com']);

        $response = $this->withExceptionHandling()->actingAs($userA)->call('POST', '/user/update', [
            'id' => $userA->id,
            'name' => 'brisa',
            'email' => 'edmundo@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'permission' => 1,
            '_token' => csrf_token()
        ]);
        $response->assertStatus(302);

        $this->assertDatabaseHas('users', ['name' => 'brisa']);
    }

    public function test_administrator_disable_access_users()
    {
       Artisan::call('migrate');

        $password = bcrypt('password');
        $userA = User::create([
            'name' => 'Ulisses',
            'email' => 'ulisses@email.com',
            'permission' => 1,
            'password' => $password
        ]);

        $userB = User::create([
            'name' => 'Homero',
            'email' => 'homero@email.com',
            'permission' => 0,
            'password' => $password
        ]);

        $this->assertDatabaseHas('users', ['email' => 'ulisses@email.com']);
        $this->assertDatabaseHas('users', ['email' => 'homero@email.com']);

        $response = $this->withExceptionHandling()->actingAs($userA)->call('POST', '/user/disable', [
            'id' => $userA->id,
            '_token' => csrf_token()
        ]);
        $response->assertStatus(302);

        $this->assertDatabaseHas('users', ['email' => 'ulisses@email.com', 'status' => 'Inactive']);
    }

     public function test_user_administor_reactivate_access_users()
    {
       Artisan::call('migrate');

        $password = bcrypt('password');
        $userA = User::create([
            'name' => 'Sullivan',
            'email' => 'sullivan@email.com',
            'permission' => 1,
            'password' => $password
        ]);

        $userB = User::create([
            'name' => 'boo',
            'email' => 'boo@email.com',
            'permission' => 0,
            'status' => 'Inactive',
            'password' => $password
        ]);

        $this->assertDatabaseHas('users', ['email' => 'sullivan@email.com']);
        $this->assertDatabaseHas('users', ['email' => 'boo@email.com']);

        $response = $this->withExceptionHandling()->actingAs($userA)->call('POST', '/user/reactivate', [
            'id' => $userB->id,
            '_token' => csrf_token()
        ]);
        $response->assertStatus(302);

        $this->assertDatabaseHas('users', ['email' => 'boo@email.com', 'status' => 'Active']);
    }
}