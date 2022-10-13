<?php

namespace App\Services\Order\Statuses;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Notifications\OrderStatus\AcceptingBuyer;
use App\Notifications\OrderStatus\AcceptingSeller;
use App\Notifications\OrderStatus\CancelledPayment;
use App\Services\Order\OrderStatusContext;

/**
 * The status after an order has been placed.
 * Have to be changed to CANCELLED by overdue or ACCEPTING after payment
 */
class Payment extends Status
{
    public function authorized(OrderStatusContext $orderContext): bool
    {
        return $this->gate($orderContext->getUser(), 'buyer', $orderContext->getOrder());
    }

    public function accept(OrderStatusContext $orderContext): void
    {
        $this->applyStatus($orderContext, OrderStatus::accepting());
        $this->sendNotifications($orderContext->getOrder());
    }

    public function decline(OrderStatusContext $orderContext): void
    {
        $this->cancel($orderContext);
    }

    public function cancel(OrderStatusContext $orderContext): void
    {
        $order = $orderContext->getOrder();
        $this->applyStatus($orderContext, OrderStatus::cancelled());
        $order->buyer->notify(new CancelledPayment($order));
    }

    private function sendNotifications(Order $order): void
    {
        $order->buyer->notify(new AcceptingBuyer($order));
        $order->seller->notify(new AcceptingSeller($order));
    }

    public function canAccept(OrderStatusContext $orderContext): bool
    {
        return true;
    }

    public function canDecline(OrderStatusContext $orderContext): bool
    {
        return true;
    }

    public function canCancel(OrderStatusContext $orderContext): bool
    {
        return true;
    }
}