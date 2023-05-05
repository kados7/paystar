<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Payment
{
    private $paymenStrategy;
    public function __construct(PaymentStrategy $paymenStrategy)
    {
        $this->paymenStrategy = $paymenStrategy;
    }

    public function create($data)
    {
        return $this->paymenStrategy->create($data);
    }

    public function callback($data)
    {
        return $this->paymenStrategy->callback($data);
    }
}
