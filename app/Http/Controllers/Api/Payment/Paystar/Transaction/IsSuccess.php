<?php

namespace App\Http\Controllers\Api\Payment\Paystar\Transaction;

use App\Http\Controllers\Api\Payment\Paystar\PaystarPayment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IsSuccess extends TransactionCheck
{
    public function check($response , $transaction , $receipt){

        if($response->status == 1){
            // dd('success ' , $response->all());

            $transaction->update([
                'ref_num' => $response->ref_num,
                'pay_success' => true,
                'transaction_id' => $response->transaction_id,
                'card_number' => $response->card_number,
                'tracking_code' => $response->tracking_code,
                'receipt' => $receipt,
            ]);

            $verify_data = [
                'amount' => $transaction->order->amount ,
                'ref_num' => $transaction->ref_num ,
                'card_number' => $transaction->card_number ,
                'tracking_code' => $transaction->tracking_code,
                'transaction_id' => $transaction->transaction_id
            ];
            $paymentVerify= new PaystarPayment();
            return $paymentVerify->verify($verify_data);
        }
        else{
            $this->successor->check($response , $transaction , $receipt);
        }
    }

}
