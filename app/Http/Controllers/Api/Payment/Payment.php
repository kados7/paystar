<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentStrategy
{
    private $paymenStrategy;
    public function __construct(PaymentStrategy $paymenStrategy)
    {
        $this->paymenStrategy = $paymenStrategy;
    }

    public function createPayment()
    {
        return $this->paymenStrategy->create('request');
    }
}
