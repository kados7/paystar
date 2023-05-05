<?php

namespace App\Http\Controllers\Api\Payment\Paystar\Transaction;

use Illuminate\Http\Request;

abstract class TransactionCheck
{
    protected $successor;
    public function setNext(TransactionCheck $transactionCheck)
    {
        $this->successor = $transactionCheck;
    }
    abstract public function check($response , $transaction , $receipt);
}
