<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\User;

class CommonUserTest extends TestCase
{
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
}