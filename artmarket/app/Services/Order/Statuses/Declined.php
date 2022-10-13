<?php

namespace App\Services\Order\Statuses;

use App\Enums\OrderStatus;
use App\Notifications\OrderStatus\CancellingBuyer;
use App\Notifications\OrderStatus\CancellingSeller;
use App\Services\Order\OrderStatusContext;
use Gate;

/**
 * The status when buyer decline the final uploaded fil
 * Can be changed to ACCEPTED by seller
 * Can be changed to CANCELLING by seller or buyer
 */
class Declined extends Accepted
{
    public function accept(OrderStatusContext $orderContext): void
    {
        $this->applyStatus($orderContext, OrderStatus::accepted());
    }

    public function decline(OrderStatusContext $orderContext): void
    {
        $this->cancel($orderContext);
    }

    public function cancel(OrderStatusContext $orderContext): void
    {
        $this->applyStatus($orderContext, OrderStatus::cancelling());

        $order = $orderContext->getOrder();
        $order->buyer->notify(new CancellingBuyer($order));
        $order->seller->notify(new CancellingSeller($order));
    }

    public function canAccept(OrderStatusContext $orderContext): bool
    {
        Gate::forUser($orderContext->getUser())->authorize('seller', $orderContext->getOrder());

        return true;
    }

    public function canDecline(OrderStatusContext $orderContext): bool
    {
        Gate::forUser($orderContext->getUser())->authorize('seller', $orderContext->getOrder());

        return true;
    }
}