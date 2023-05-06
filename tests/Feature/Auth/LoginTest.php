<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_login_validation_required_fields(): void
    {
        $response = $this->post(route('login'),[
            'email' => '',
            // 'email' => '',
        ]);
        $response->assertJson([
            'code' => 401,
            'message' => 'The email field is required.'
        ]);
    }

    public function test_login_with_wrong_email(): void
    {
        $user= User::factory()->state([
            'email' => 'test@test.com',
            'password' => Hash::make('secret'),
        ])->create();

        $response = $this->post(route('login'),[
            'email' => 'wrongMail@test.com',
            'password' => 'secret',
        ]);
        $response->assertJson([
            'code' => 401,
            'message' => 'کاربری با این ایمیل وجود ندارد'
        ]);
    }

    public function test_login_with_wrong_password(): void
    {
        \Illuminate\Support\Facades\Artisan::call('passport:install');

        $user= User::factory()->state([
            'email' => 'test@test.com',
            'password' => Hash::make('secret'),
        ])->create();

        $response = $this->post(route('login'),[
            'email' => $user->email,
            'password' => '1',
        ]);
        $response->assertJson([
            'code' => 401,
            'message' => 'پسورد اشتباه است.'
        ]);
    }

    public function test_login_works(): void
    {
       \Illuminate\Support\Facades\Artisan::call('passport:install');

        $user= User::factory()->state([
            'password' => Hash::make("secret"),
        ])->create();

        $response = $this->post(route('login'),[
            'email' => $user->email,
            'password' => 'secret',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['code','token']);

    }

    public function test_create_access_token_after_loggin(): void
    {
       \Illuminate\Support\Facades\Artisan::call('passport:install');

        $user= User::factory()->state([
            'password' => Hash::make("secret"),
        ])->create();

        $response = $this->post(route('login'),[
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $this->assertDatabaseHas('oauth_access_tokens',['user_id' => $user->id]);

    }
}
