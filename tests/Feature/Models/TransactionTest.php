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

class TransactionTest extends TestCase
{
    use RefreshDatabase,ModelHelperInsertTesting;

    protected function model():Model{
        return new Transaction();
    }

    public function test_transaction_has_relation_with_order_belongsto(){

        $transaction= Transaction::factory()->for(Order::factory())->create();

        $this->assertTrue(isset($transaction->order->id));
        $this->assertTrue($transaction->order instanceof Order);
    }
}
