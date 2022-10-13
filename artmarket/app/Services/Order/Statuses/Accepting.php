<?php

namespace App\Services\Order\Statuses;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Notifications\OrderStatus\AcceptedBuyer;
use App\Notifications\OrderStatus\AcceptedSeller;
use App\Notifications\OrderStatus\CancelledAcceptingBuyer;
use App\Notifications\OrderStatus\CancelledAcceptingSeller;
use App\Services\Order\OrderStatusContext;
use Gate;

/**
 * The status when buyer do payment
 * Can be changed to ACCEPTED by seller
 * Can be changed to CANCELLED by seller or buyer or system
 */
class Accepting extends Status
{
    public function authorized(OrderStatusContext $orderContext): bool
    {
        return $this->gate($orderContext->getUser(), 'view', $orderContext->getOrder());
    }

    public function accept(OrderStatusContext $orderContext): void
    {
        $this->applyStatus($orderContext, OrderStatus::accepted());
        $this->sendNotifications($orderContext->getOrder());
    }

    public function decline(OrderStatusContext $orderContext): void
    {
        $this->cancel($orderContext);
    }

    public function cancel(OrderStatusContext $orderContext): void
    {
        $this->applyStatus($orderContext, OrderStatus::cancelled());

        $order = $orderContext->getOrder();
        $order->buyer->notify(new CancelledAcceptingBuyer($order));
        $order->seller->notify(new CancelledAcceptingSeller($order));
    }

    private function sendNotifications(Order $order): void
    {
        $order->buyer->notify(new AcceptedBuyer($order));
        $order->seller->notify(new AcceptedSeller($order));
    }

    public function canAccept(OrderStatusContext $orderContext): bool
    {
        Gate::forUser($orderContext->getUser())->authorize('accept', $orderContext->getOrder());

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