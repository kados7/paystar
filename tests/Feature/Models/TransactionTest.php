<?php

namespace Tests\Feature\Models;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase,ModelHelperInsertTesting;

    protected function model():Model{
        return new Transaction();
    }

    public function test_transaction_has_relation_with_user_belongsto(){

        $transaction= Transaction::factory()->for(User::factory())->create();

        $this->assertTrue(isset($transaction->user->id));
        $this->assertTrue($transaction->user instanceof User);
    }


    public function test_transaction_has_relation_with_product_belongsto(){

        $transaction= Transaction::factory()->for(Product::factory())->create();

        $this->assertTrue(isset($transaction->product->id));
        $this->assertTrue($transaction->product instanceof Product);
    }
}
