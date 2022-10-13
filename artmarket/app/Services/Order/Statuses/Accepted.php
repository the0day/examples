<?php

namespace App\Services\Order\Statuses;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Notifications\OrderStatus\ApprovingBuyer as BuyerNotification;
use App\Notifications\OrderStatus\ApprovingSeller as SellerNotification;
use App\Notifications\OrderStatus\CancellingBuyer;
use App\Notifications\OrderStatus\CancellingSeller;
use App\Services\Order\OrderStatusContext;
use Assert\Assertion;
use Gate;

/**
 * The status when seller accept the order after payment
 * Can be changed to APPROVING after seller upload the work
 * Can be changed to CANCELLING by seller or buyer
 */
class Accepted extends Status
{
    public function authorized(OrderStatusContext $orderContext): bool
    {
        return $this->gate($orderContext->getUser(), 'view', $orderContext->getOrder());
    }

    public function accept(OrderStatusContext $orderContext): void
    {
        $this->applyStatus($orderContext, OrderStatus::approving());
        $this->sendNotifications($orderContext->getOrder());
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

    private function sendNotifications(Order $order): void
    {
        $order->buyer->notify(new BuyerNotification($order));
        $order->seller->notify(new SellerNotification($order));
    }

    public function canAccept(OrderStatusContext $orderContext): bool
    {
        Gate::forUser($orderContext->getUser())->authorize('upload', $orderContext->getOrder());

        Assertion::true($orderContext->getOrder()->getFinalMedia()->isNotEmpty(), __('order.error.final_work_must_be_uploaded'));

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