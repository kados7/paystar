<?php

namespace App\Http\Controllers\Api\Payment\Paystar;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Payment\PaymentStrategy;
use App\Http\Controllers\Api\Payment\Paystar\Transaction\IsCardNumberNotValid;
use App\Http\Controllers\Api\Payment\Paystar\Transaction\IsFailed;
use App\Http\Controllers\Api\Payment\Paystar\Transaction\IsSuccess;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class PaystarPayment implements PaymentStrategy
{

    public function create($request){

        $order =Order::create([
            'user_id' => $request->user()->id,
            'product_id' =>$request->product_id,
            'cart_id' =>$request->cart_id,
            'payment_gateway' =>$request->payment_gateway,
        ]);
        // make sign for Http Request
        $signData= $request->price."#".$order->id."#".route('v1.paystar.callback');
        $sign = hash_hmac('SHA512',$signData,env('PAYSTAR_SIGN_KEY'),false);

        $cart = Cart::find($request->cart_id);

        // send Http Request
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTAR_GATEWAY_ID'),
        ])->post( env('PAYSTAR_CREATE_URL') , [
            'amount' => $request->price,
            'order_id' => $order->id,
            'callback' => route('v1.paystar.callback'),
            'sign' => $sign,
            'card_number' => strval($cart->number)
        ]);

        $response=json_decode($response);

        // check response status
        if($response->status == 1) {
            $order->update([
                'amount' => $response->data->payment_amount
            ]);

            $transaction= Transaction::create([
                'order_id' => $order->id,
                'ref_num' => $response->data->ref_num,
            ]);

            return [
                'code'=> 200,
                'transaction' => $transaction,
                'response' => $response,
            ];
        }else {
            return [
                'code'=> 401,
                'data' => $response,
            ];
        }

    }


    public function callback($request){
        $transaction = Transaction::where('ref_num',$request->ref_num)->first();
        $receipt = $transaction->id.'.'.Carbon::now()->timestamp.'.'.$transaction->order->user_id;

        //Chain of Responsibility - check Payment Situation
        $isFailed = new IsFailed();
        $isCartNumberNotValid = new IsCardNumberNotValid();
        $isSuccess = new IsSuccess();

        $isFailed->setNext($isCartNumberNotValid);
        $isCartNumberNotValid->setNext($isSuccess);

        return $isFailed->check($request,$transaction,$receipt);
    }

    function verify($verify_data) {

        $transaction = Transaction::where('transaction_id',$verify_data['transaction_id'])->first();

        // make sign for Http Request
        $signData=$verify_data['amount']."#".$verify_data['ref_num']."#".$verify_data['card_number']."#".$verify_data['tracking_code'];
        $sign =hash_hmac('SHA512',$signData,env('PAYSTAR_SIGN_KEY'),false);

        // send Http Request
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTAR_GATEWAY_ID'),
        ])->post( env('PAYSTAR_VERIFY_URL') , [
            'ref_num' => $verify_data['ref_num'],
            'amount' => $verify_data['amount'],
            'sign' => $sign,
        ]);
        $response=json_decode($response);


        // check response status
        if($response->status == 1){
            $transaction->update([
                'verify' =>true
            ]);
            $user = $transaction->order->user;
            $product=$transaction->order->product;
            $user->products()->attach($product);
            return redirect('/payment/transaction/'.$verify_data['transaction_id'].'/success');
        }
        else{
            return redirect('/payment/transaction/'.$verify_data['transaction_id'].'/failed');
        }
    }
}
