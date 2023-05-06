<?php

namespace Tests\Feature\Controller;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_carts_index(): void
    {
        \Illuminate\Support\Facades\Artisan::call('passport:install');
        $count=rand(1,5);
        $user=User::factory()->hasCarts($count)->create();
        $token = $user->createToken('paystore')->accessToken;

        $response =$this->withHeaders([
            'Content-Type'=> 'application/json',
            'Authorization'=>'Bearer ' . $token
        ])->post(route('v1.profile.cart.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'code','carts'
        ]);
        $jsonResponse = $response->decodeResponseJson();
        $this->assertCount($count,$jsonResponse['carts']);
    }

    public function test_user_add_cart_validation_work(): void
    {
        \Illuminate\Support\Facades\Artisan::call('passport:install');
        $user=User::factory()->create();
        $token = $user->createToken('paystore')->accessToken;

        $cart=Cart::factory()->make()->toArray();

        $response =$this->withHeaders([
            'Content-Type'=> 'application/json',
            'Authorization'=>'Bearer ' . $token
        ])->postJson(route('v1.cart.add'),[
            'name' => '',
            'number' => $cart['number'],
            'bank' => $cart['bank'],
        ]);

        $response->assertOk();
        $response->assertJson([
            'code' => 401,
            'message' => 'The name field is required.'
        ]);
        $this->assertDatabaseMissing('carts',$cart);
    }


    public function test_user_add_cart_works(): void
    {
        \Illuminate\Support\Facades\Artisan::call('passport:install');
        $user=User::factory()->create();
        $token = $user->createToken('paystore')->accessToken;

        $cart=Cart::factory()->make()->toArray();
        $cart['user_id'] = $user->id;
        $response = $this->actingAs($user)->postJson(route('v1.cart.add'),$cart,[
            'Content-Type'=> 'application/json',
            'Authorization'=>'Bearer ' . $token
        ]);
        // dd($response->decodeResponseJson() , $cart);
        $response->assertOk();
        $response->assertJsonStructure(['code','cart','carts']);
        $this->assertDatabaseHas('carts',$cart);
    }

    public function test_get_user_Purchased_products(): void
    {
        \Illuminate\Support\Facades\Artisan::call('passport:install');
        $count=rand(1,3);
        $user=User::factory()->hasProducts($count)->create();
        $token = $user->createToken('paystore')->accessToken;

        $response = $this->post(route('v1.profile.user.products'),[],[
            'Content-Type'=> 'application/json',
            'Authorization'=>'Bearer ' . $token
        ]);

        $response->assertJsonStructure(['code','products']);
        $jsonResponse= $response->decodeResponseJson();
        $this->assertCount($count,$jsonResponse['products']);


    }
}
