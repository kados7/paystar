<?php

namespace Tests\Feature\Models;

use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase , ModelHelperInsertTesting;

    protected function model():Model{
        return new Product();
    }

    public function test_product_has_relation_with_user_belongstomany(){
        $count=rand(1,3);
        $product= Product::factory()->hasUsers($count)->create();

        $this->assertCount($count,$product->users);
        $this->assertTrue($product->users->first() instanceof User);
    }

    public function test_product_has_relation_with_order_hasmany(){
        $count=rand(1,5);
        $product= Product::factory()->hasOrders($count)->create();

        $this->assertCount($count,$product->orders);
        $this->assertTrue($product->orders->first() instanceof Order);
    }
}
