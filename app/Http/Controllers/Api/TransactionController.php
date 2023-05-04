<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function show(Transaction $transaction, Request $request){
        // return 'sss';
        return [
            'code' => 200,
            'transaction' => $transaction,
            'order' => $transaction->order,
            'cart' => $transaction->order->cart,
        ];
    }
}
