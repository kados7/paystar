<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_guest_can_not_access_loggedout(): void
    {
        \Illuminate\Support\Facades\Artisan::call('passport:install');
        $user= User::factory()->create();

        $response=$this->post(route('logout'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();

    }

     public function test_user_can_access_loggedout_and_access_token_delete(): void
     {
        \Illuminate\Support\Facades\Artisan::call('passport:install');
        $user= User::factory()->create();
        $token = $user->createToken('paystore')->accessToken;

        $response=$this->withHeaders([
            // 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            'Authorization'=>'Bearer ' . $token
        ])->post(route('logout'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['code','message']);
        $this->assertDatabaseMissing('personal_access_tokens',['tokenable_id'=>$user->id]);
     }
}
