<?php

namespace App\Http\Controllers\Api\Payment\Paystar\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IsCardNumberNotValid extends TransactionCheck
{
    public function check($response , $transaction , $receipt){

        $user_submit_cart_number = $transaction->order->cart->number;
        $cartWithStar = substr( $user_submit_cart_number,0,6)."******".substr( $user_submit_cart_number,12,4);

        if($response->status == 1 && $cartWithStar !== $response->card_number){
            // dd('Cart Fail' , $response->all(),$cartWithStar ,$response->card_number);

            $transaction->update([
                'ref_num' => $response->ref_num,
                'pay_success' => true,
                'transaction_id' => $response->transaction_id,
                'card_number' => $response->card_number,
                'tracking_code' => $response->tracking_code,
                'receipt' => $receipt,
                'verify' => false,
            ]);

            return redirect('/payment/transaction/'.$transaction->transaction_id.'/failed/cartNumber');

        }
        else{
            return $this->successor->check($response , $transaction , $receipt);
        }
    }

}
