<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Services\Order\OrderStatusContext;


class OrderStatusService
{
    public function accept(User $user, Order $order)
    {
        $orderStatusContext = new OrderStatusContext($order, $user);
        $orderStatusContext->accept();
    }


    public function decline(User $user, Order $order)
    {
        $orderStatusContext = new OrderStatusContext($order, $user);
        $orderStatusContext->decline();
    }

    public function cancel(User $user, Order $order)
    {
        $orderStatusContext = new OrderStatusContext($order, $user);
        $orderStatusContext->cancel();
    }

}
