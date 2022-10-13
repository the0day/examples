<?php

namespace App\PaymentGateways;

interface PaymentGateway
{
    public function checkout();

    public function complete();
}