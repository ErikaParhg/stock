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
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);

    }


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

    public function test_common_users_not_register_users()
    {
        Artisan::call('migrate');

        $password = bcrypt('password');
        $user = User::create([
            'name' => 'Edneia',
            'email' => 'edneia@email.com',
            'permission' => 0,
            'password' => $password
        ]);

        $this->assertDatabaseHas('users', ['email' => 'edneia@email.com']);

        $response = $this->withExceptionHandling()->actingAs($user)->call('POST', '/user/register', [
            'name' => 'name',
            'email' => 'user@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'permission' => 0,
            '_token' => csrf_token()
        ]);

        $this->assertDatabaseMissing('users', ['email' => 'user@email.com']);
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

        $response = $this->withExceptionHandling()
                         ->call('GET', '/user');

        $response->assertStatus(403);

        $response = $this
                         ->actingAs($userA)
                         ->call('GET', '/user');

        $response->assertStatus(200);

         $response = $this->withExceptionHandling()
                         ->actingAs($userB)
                         ->call('GET', '/user');

        $response->assertStatus(403);        
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

        $response = $this->withExceptionHandling()
                         ->call('GET', '/user');

        $response->assertStatus(403);

        $response = $this->withExceptionHandling()
                         ->actingAs($userB)
                         ->call('GET', '/user');

        $response->assertStatus(403);

        $response = $this->withExceptionHandling()
                         ->actingAs($userA)
                         ->call('GET', '/user');

        $response->assertStatus(200);

        foreach ($users as $user) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
        }
    }


     public function test_administrators_edit_users(){
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

        $response = $this->withExceptionHandling()->call('POST', '/user/update', [
            'id' => $userA->id,
            'name' => 'brisa',
            'email' => 'edmundo@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'permission' => 1,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);

        $response = $this->withExceptionHandling()->actingAs($userB)->call('POST', '/user/update', [
            'id' => $userA->id,
            'name' => 'brisa',
            'email' => 'edmundo@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'permission' => 1,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);

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


        $response = $this->withExceptionHandling()->call('POST', '/user/disable', [
            'id' => $userA->id,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);

        $response = $this->withExceptionHandling()->actingAs($userB)->call('POST', '/user/disable', [
            'id' => $userA->id,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);

        $response = $this->withExceptionHandling()->actingAs($userA)->call('POST', '/user/disable', [
            'id' => $userA->id,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', ['email' => 'ulisses@email.com', 'status' => 'Inactive']);
    }


     public function test_user_disabled_not_login()
     {
        Artisan::call('migrate');

        $password = bcrypt('password');
        $userA = User::create([
            'name' => 'Samuel Hahnemann',
            'email' => 'hahnemann@email.com',
            'permission' => 1,
            'password' => $password
        ]);

        $userA->status = 'Inactive';
        $userA->save();

        $response = $this->withExceptionHandling()->call('POST', '/login', [
            'email' => 'hahnemann@email.com',
            'password' => 'password',
            '_token' => csrf_token()
        ]);

        $this->assertDatabaseHas('users', ['email' => 'hahnemann@email.com', 'status' => 'Inactive']);

        $response->assertStatus(302);
        $response->assertRedirect('/');

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

        $response = $this->withExceptionHandling()->call('POST', '/user/reactivate', [
            'id' => $userB->id,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);

        $response = $this->withExceptionHandling()->actingAs($userB)->call('POST', '/user/reactivate', [
            'id' => $userB->id,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);

        $response = $this->withExceptionHandling()->actingAs($userA)->call('POST', '/user/reactivate', [
            'id' => $userB->id,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', ['email' => 'boo@email.com', 'status' => 'Active']);
    }
}



