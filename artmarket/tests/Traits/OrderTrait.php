<?php

namespace Tests\Traits;

use App\Models\Offer;
use App\Models\Order;
use App\Models\User;

trait OrderTrait
{
    protected function createOrderAndMarkPaid(?Offer $offer = null, ?User $customer = null): Order
    {
        $order = $this->createOrder($offer, $customer);

        $this->userService->addCredit($customer ?? $order->buyer, $order->total_cost);
        $this->orderService->doPayment($order);

        return $order;
    }

    protected function payOrder(Order $order): void
    {
        $this->userService->addCredit($order->buyer, $order->total_cost);
        $this->orderService->doPayment($order);
    }
}