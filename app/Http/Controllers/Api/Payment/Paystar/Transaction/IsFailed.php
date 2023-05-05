<?php

namespace App\Http\Controllers\Api\Payment\Paystar\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IsFailed extends TransactionCheck
{
    public function check($response , $transaction , $receipt){

        if(! $response->status == 1){
            // dd('Failed' , $response->all());
            $transaction->update([
                'pay_success' => false,
                'transaction_id' => $response->transaction_id,
                'receipt' => $receipt,
            ]);

            return redirect('/payment/transaction/'.$transaction->transaction_id.'/failed');
        }
        else{
            return $this->successor->check($response , $transaction , $receipt);
        }
    }

}
