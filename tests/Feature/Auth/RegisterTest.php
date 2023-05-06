<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_register_validation_required_fields(): void
    {
        $response = $this->post(route('register'),[
            'name' => '',
            // 'email' => '',
        ]);
        $response->assertJson([
            'code' => 401,
            'message' => 'The name field is required.'
        ]);
        $this->assertDatabaseCount('users',0);
    }

    public function test_register_validation_unique_email(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('register'),[
            'name' => $user->name,
            'email' => $user->email,
            'password' => '123456',
            'password_c' => '123456',
        ]);
        $response->assertJson([
            'code' => 401,
            'message' => 'The email has already been taken.'
        ]);
        $this->assertDatabaseCount('users',1);
    }

    public function test_register_validation_same_password(): void
    {
        $user = User::factory()->make()->toArray();

        $response = $this->post(route('register'),[
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => '123456',
            'password_c' => '1234',
        ]);
        $response->assertJson([
            'code' => 401,
            'message' => 'The password c field must match password.'
        ]);
        $this->assertDatabaseMissing('users',$user);
    }

    public function test_create_user_in_database(): void
    {
        \Illuminate\Support\Facades\Artisan::call('passport:install');
        $user= User::factory()->make()->toArray();
        // Hash::make('123456');

        $response = $this->post(route('register'),array_merge($user, [
            'password' => 123456,
            'password_c' => 123456,
        ]));
        $response->assertStatus(200);

        $this->assertDatabaseHas('users' ,$user);
    }
}
