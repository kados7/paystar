<?php

namespace App\Http\Controllers\Api\Payment\SaderatBank;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Payment\PaymentStrategy;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class SaderatPayment implements PaymentStrategy
{
    public function create($request){

    }


    public function callback($request){

    }

    function verify($amount,$ref_num,$card_number, $tracking_code , $transaction_id) {

    }
}
