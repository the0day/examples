<?php

namespace App\Services\Order\Statuses;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Notifications\OrderStatus\CancellingBuyer;
use App\Notifications\OrderStatus\CancellingSeller;
use App\Notifications\OrderStatus\DeclinedBuyer;
use App\Notifications\OrderStatus\DeclinedSeller;
use App\Notifications\OrderStatus\DeliveredBuyer as BuyerNotification;
use App\Notifications\OrderStatus\DeliveredSeller as SellerNotification;
use App\Services\Order\OrderStatusContext;
use Gate;

/**
 * The status when seller uploads the final work and waiting for an approval by buyer
 * Can be changed to DELIVERED or DECLINED by buyer
 * Can be changed to CANCELLING by seller or buyer
 */
class Approving extends Status
{
    public function authorized(OrderStatusContext $orderContext): bool
    {
        return $this->gate($orderContext->getUser(), 'view', $orderContext->getOrder());
    }

    public function accept(OrderStatusContext $orderContext): void
    {
        $this->applyStatus($orderContext, OrderStatus::delivered());
        $this->sendNotifications($orderContext->getOrder());
    }

    public function decline(OrderStatusContext $orderContext): void
    {
        $this->applyStatus($orderContext, OrderStatus::declined());

        $order = $orderContext->getOrder();
        $order->buyer->notify(new DeclinedBuyer($order));
        $order->seller->notify(new DeclinedSeller($order));
    }

    public function cancel(OrderStatusContext $orderContext): void
    {
        $this->applyStatus($orderContext, OrderStatus::cancelling());

        $order = $orderContext->getOrder();
        $order->buyer->notify(new CancellingBuyer($order));
        $order->seller->notify(new CancellingSeller($order));
    }

    private function sendNotifications(Order $order): void
    {
        $order->buyer->notify(new BuyerNotification($order));
        $order->seller->notify(new SellerNotification($order));
    }

    public function canAccept(OrderStatusContext $orderContext): bool
    {
        return $this->canDecline($orderContext);
    }

    public function canDecline(OrderStatusContext $orderContext): bool
    {
        Gate::forUser($orderContext->getUser())->authorize('approve', $orderContext->getOrder());

        return true;
    }

    public function canCancel(OrderStatusContext $orderContext): bool
    {
        return true;
    }
}