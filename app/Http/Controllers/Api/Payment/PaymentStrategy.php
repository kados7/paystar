<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

interface PaymentStrategy
{
    public function create($request);
    public function callback($request);
    public function verify($verify_data);

}
