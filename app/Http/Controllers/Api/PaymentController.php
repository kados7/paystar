<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public $api = 'test';
    public $paymentCreateUrl = "https://core.paystar.ir/api/pardakht/create";
    public $paymentVerifyUrl = "https://core.paystar.ir/api/pardakht/verify";
    public $gatewayId = "0yovdk2l6e143";
    public $signKey = "9A3EC03483556C73714510C507529DF70A1228C83477D1455E0511BD72C5AAB8A6715A414AA48B7C905FCEF45868BD26DA58196EF29C77C194C9F14A4B47456CC6454E9D50B388D6FC5AC91BB08B234A8060FDC85B1CEC32CA036DC907F8A4A635D9CBB9CAA31B42549B8D70B2CE5EDE8274FFB55DABFE92D76BC42D91696FAF";

    public function goToPayment(Request $request){

        $order =Order::create([
            'user_id' => $request->user()->id,
            'product_id' =>$request->product_id,
            'cart_id' =>$request->cart_id,
            // 'price' =>$request->price,
        ]);

        $signData=$request->price."#".$order->id."#".route('v1.paystar.callback');
        $sign =hash_hmac('SHA512',$signData,$this->signKey,false);
        // $cart_number = Cart::find($request->cart_id)->number;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->gatewayId,
        ])->post( $this->paymentCreateUrl , [
            'amount' => $request->price,
            'order_id' => $order->id,
            'callback' => route('v1.paystar.callback'),
            'sign' => $sign,
            // 'card_number' => $cart_number,
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
        }else {
            return [
                'code'=> 401,
                'data' => $response->message,
            ];
        }
        return [
            'code'=> 200,
            'transaction' => $transaction,
            'response' => $response,
        ];
    }


    public function callbackPaystar(Request $request){
        $transaction = Transaction::where('ref_num',$request->ref_num)->first();
        $receipt = $transaction->id.'.'.Carbon::now()->timestamp.'.'.$transaction->order->user_id;

        if($request->status == 1){
            $transaction->update([
                'ref_num' => $request->ref_num,
                'pay_success' => true,
                'transaction_id' => $request->transaction_id,
                'card_number' => $request->card_number,
                'tracking_code' => $request->tracking_code,
                'receipt' => $receipt,
            ]);



            // return redirect('/payment/transaction/'.$transaction->transaction_id.'/success');
            $this->verify($transaction->order->amount , $transaction->ref_num , $transaction->card_number , $transaction->tracking_code,$transaction->transaction_id);
        }
        else{
            $transaction->update([
                'pay_success' => false,
                'transaction_id' => $request->transaction_id,
                'receipt' => $receipt,
            ]);

            $order_id = $transaction->order->id;
            return redirect('/payment/transaction/'.$transaction->transaction_id.'/failed');
        }
    }

    function verify($amount,$ref_num,$card_number, $tracking_code , $transaction_id) {

        // amount#ref_num#card_number#tracking_code
        $signData=$amount."#".$ref_num."#".$card_number."#".$tracking_code;

        $sign =hash_hmac('SHA512',$signData,$this->signKey,false);
        // dd($amount, $ref_num, $card_number, $tracking_code , $transaction_id,$sign,$signData);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->gatewayId,
        ])->post( $this->paymentVerifyUrl , [
            'ref_num' => $ref_num,
            'amount' => $amount,
            'sign' => $sign,
        ]);


        $response=json_decode($response);
        $transaction = Transaction::where('transaction_id',$transaction_id)->first();
        // dd($response,$transaction,$ref_num, $amount,$card_number, $tracking_code , $transaction_id,$sign,$signData);
        if($response->status == 1){
            $user = $transaction->order->user;
            $product=$transaction->order->product;
            $user->products()->attach($product);
            return redirect('/payment/transaction/'.$transaction_id.'/success');
        }else{
            return redirect('/payment/transaction/'.$transaction_id.'/failed');
        }
    }
}
