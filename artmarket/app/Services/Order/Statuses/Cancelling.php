<?php

namespace App\Services\Order\Statuses;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Notifications\OrderStatus\Cancelled as CancelledNotification;
use App\Services\Order\OrderStatusContext;
use Gate;

/**
 * The status can be initiated by seller or buyer or system
 * Can be changed to ACCEPTED with confirmation of counterparty or system
 * Can be changed to CANCELLED with confirmation of counterparty or system
 */
class Cancelling extends Status
{
    public function authorized(OrderStatusContext $orderContext): bool
    {
        return true;
    }

    public function accept(OrderStatusContext $orderContext): void
    {
        $this->applyStatus($orderContext, OrderStatus::cancelled());
        $this->sendNotifications($orderContext->getOrder());
    }

    public function decline(OrderStatusContext $orderContext): void
    {
        $this->applyStatus($orderContext, OrderStatus::accepted());
    }

    public function cancel(OrderStatusContext $orderContext): void
    {

    }

    public function canAccept(OrderStatusContext $orderContext): bool
    {
        return $this->canAcceptOrDecline($orderContext);
    }

    public function canDecline(OrderStatusContext $orderContext): bool
    {
        return $this->canAcceptOrDecline($orderContext);
    }

    public function canCancel(OrderStatusContext $orderContext): bool
    {
        return false;
    }

    private function sendNotifications(Order $order): void
    {
        $order->buyer->notify(new CancelledNotification($order));
        $order->seller->notify(new CancelledNotification($order));
    }

    private function canAcceptOrDecline(OrderStatusContext $orderContext): bool
    {
        $order = $orderContext->getOrder();
        $counterparty = $order->cancelled_by_id != $order->seller->id ? 'seller' : 'buyer';
        Gate::forUser($orderContext->getUser())->authorize($counterparty, $order);

        return true;
    }
}