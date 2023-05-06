<?php

namespace Tests\Feature\Models;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase,ModelHelperInsertTesting;

    protected function model():Model{
        return new Order();
    }

    public function test_order_has_relation_with_user_belongsto(){
        $order=Order::factory()->for(User::factory())->create();
        $this->assertTrue(isset($order->user->id));
        $this->assertTrue($order->user instanceof User);
    }

    public function test_order_has_relation_with_product_belongsto(){
        $order=Order::factory()->for(Product::factory())->create();
        $this->assertTrue(isset($order->product->id));
        $this->assertTrue($order->product instanceof Product);
    }

    public function test_order_has_relation_with_cart_belongsto(){
        $order=Order::factory()->for(Cart::factory())->create();
        $this->assertTrue(isset($order->cart->id));
        $this->assertTrue($order->cart instanceof Cart);
    }

}
