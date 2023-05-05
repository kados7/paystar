<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Api\Payment\Paystar\PaystarPayment;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;


class PaymentController extends Controller
{
    public function goToPayment(Request $request){
        if($request->payment_gateway == 'paystar'){
            $payment = new Payment(new PaystarPayment);
            return $payment->create($request);
        }
    }

    public function paymentCallback(Request $request){

        $order = Order::where('id',$request->order_id)->first();

        if($order->payment_gateway == 'paystar'){
            $payment = new Payment(new PaystarPayment);
            return $payment->callback($request);
        }
    }

    public function paymentVerify(Request $request){

        $order = Order::where('id',$request->order_id)->first();

        if($order->payment_gateway == 'paystar'){
            $payment = new Payment(new PaystarPayment);
            return $payment->callback($request);
        }
    }
}
