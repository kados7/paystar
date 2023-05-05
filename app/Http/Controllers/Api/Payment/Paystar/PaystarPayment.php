<?php

namespace App\Http\Controllers\Api\Payment\Paystar;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Payment\PaymentStrategy;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class PaystarPayment implements PaymentStrategy
{
    private $paystarCreateUrl = "https://core.paystar.ir/api/pardakht/create";
    private $paystarVerifyUrl = "https://core.paystar.ir/api/pardakht/verify";
    private $paystarGatewayId = "0yovdk2l6e143";
    private $paystarSignKey = "9A3EC03483556C73714510C507529DF70A1228C83477D1455E0511BD72C5AAB8A6715A414AA48B7C905FCEF45868BD26DA58196EF29C77C194C9F14A4B47456CC6454E9D50B388D6FC5AC91BB08B234A8060FDC85B1CEC32CA036DC907F8A4A635D9CBB9CAA31B42549B8D70B2CE5EDE8274FFB55DABFE92D76BC42D91696FAF";

    public function create($request){

        $order =Order::create([
            'user_id' => $request->user()->id,
            'product_id' =>$request->product_id,
            'cart_id' =>$request->cart_id,
            'payment_gateway' =>$request->payment_gateway,
            // 'price' =>$request->price,
        ]);

        $signData=$request->price."#".$order->id."#".route('v1.paystar.callback');
        $sign =hash_hmac('SHA512',$signData,$this->paystarSignKey,false);
        // $cart_number = Cart::find($request->cart_id)->number;

        $cart = Cart::find($request->cart_id);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->paystarGatewayId,
        ])->post( $this->paystarCreateUrl , [
            'amount' => $request->price,
            'order_id' => $order->id,
            'callback' => route('v1.paystar.callback'),
            'sign' => $sign,
            'card_number' => strval($cart->number)
        ]);

        $response=json_decode($response);

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


        $user_submit_cart_number = $transaction->order->cart->number;
        $cartWithStar = substr( $user_submit_cart_number,0,6)."******".substr( $user_submit_cart_number,12,4);

        if($request->status == 1 && $cartWithStar !== $request->card_number){

            // dd($request->all(), $cartWithStar);
            $transaction->update([
                'ref_num' => $request->ref_num,
                'pay_success' => true,
                'transaction_id' => $request->transaction_id,
                'card_number' => $request->card_number,
                'tracking_code' => $request->tracking_code,
                'receipt' => $receipt,
                'verify' => false,
            ]);
            return redirect('/payment/transaction/'.$transaction->transaction_id.'/failed/cartNumber');

        }
        if($request->status == 1){

            $transaction->update([
                'ref_num' => $request->ref_num,
                'pay_success' => true,
                'transaction_id' => $request->transaction_id,
                'card_number' => $request->card_number,
                'tracking_code' => $request->tracking_code,
                'receipt' => $receipt,
            ]);

            $verify_data = [
                'amount' => $transaction->order->amount ,
                'ref_num' => $transaction->ref_num ,
                'card_number' => $transaction->card_number ,
                'tracking_code' => $transaction->tracking_code,
                'transaction_id' => $transaction->transaction_id
            ];
            return $this->verify($verify_data);
        }
        else{
            $transaction->update([
                'pay_success' => false,
                'transaction_id' => $request->transaction_id,
                'receipt' => $receipt,
            ]);

            return redirect('/payment/transaction/'.$transaction->transaction_id.'/failed');
        }
    }

    function verify($verify_data) {

        // amount#ref_num#card_number#tracking_code
        $signData=$verify_data['amount']."#".$verify_data['ref_num']."#".$verify_data['card_number']."#".$verify_data['tracking_code'];

        $sign =hash_hmac('SHA512',$signData,$this->paystarSignKey,false);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->paystarGatewayId,
        ])->post( $this->paystarVerifyUrl , [
            'ref_num' => $verify_data['ref_num'],
            'amount' => $verify_data['amount'],
            'sign' => $sign,
        ]);


        $response=json_decode($response);
        $transaction = Transaction::where('transaction_id',$verify_data['transaction_id'])->first();

        if($response->status == 1){
            // dd('Yes',$response,$transaction,$ref_num, $amount,$card_number, $tracking_code , $transaction_id,$sign,$signData);
            $transaction->update([
                'verify' =>true
            ]);
            $user = $transaction->order->user;
            $product=$transaction->order->product;
            $user->products()->attach($product);
            return redirect('/payment/transaction/'.$verify_data['transaction_id'].'/success');
        }
        else{
            // dd("/payment/transaction/".$transaction_id."/failed");
            return redirect('/payment/transaction/'.$verify_data['transaction_id'].'/failed');
        }
    }
}
