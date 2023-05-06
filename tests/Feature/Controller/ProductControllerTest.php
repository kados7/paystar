<?php

namespace Tests\Feature\Controller;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_see_products_in_index_page(): void
    {
        $count= rand(1,10);
        $product= Product::factory()->count($count)->create();
        $response =$this->get(route('v1.home.product.index'));
        $response->assertOk();
        $response->assertJsonCount($count);
    }

    public function test_user_can_see_product_in_single_page(): void
    {
        $product= Product::factory()->create();
        $response =$this->get(route('v1.home.product.show',$product->id));
        $response->assertOk();
        $response->assertJsonStructure(['product']);
        $response->getContent();
    }

    public function test_check_a_product_is_purchased_by_user(): void
    {
        \Illuminate\Support\Facades\Artisan::call('passport:install');

        $user=User::factory()->hasProducts(1)->create();
        $token = $user->createToken('paystore')->accessToken;

        $response =$this->withHeaders([
            // 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            'Authorization'=>'Bearer ' . $token
        ])->post(route('v1.home.product.isPurchased' , $user->products->first()->id));
        $response->assertSee(true);
    }
}
