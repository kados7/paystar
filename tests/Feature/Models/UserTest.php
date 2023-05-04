<?php

namespace Tests\Feature\Models;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase,ModelHelperInsertTesting;

    protected function model():Model{
        return new User();
    }

    public function test_user_has_relation_with_product_belongstomany(){
        $count=rand(1,3);
        $user= User::factory()->hasProducts($count)->create();

        $this->assertCount($count,$user->products);
        $this->assertTrue($user->products->first() instanceof Product);
    }

    public function test_user_has_relation_with_transaction_hasmany(){
        $count=rand(1,5);
        $user= User::factory()->hasTransactions($count)->create();

        $this->assertCount($count,$user->transactions);
        $this->assertTrue($user->transactions->first() instanceof Transaction);
    }

}
