<?php

namespace App\Services\Order\Statuses;

use App\Services\Order\OrderStatusContext;

/**
 * The final status of delivered order
 * also @see Cancelled (another final status)
 */
class Delivered extends Status
{
    public function authorized(OrderStatusContext $orderContext): bool
    {
        return true;
    }

    public function accept(OrderStatusContext $orderContext): void
    {

    }

    public function decline(OrderStatusContext $orderContext): void
    {

    }

    public function cancel(OrderStatusContext $orderContext): void
    {

    }

    public function canAccept(OrderStatusContext $orderContext): bool
    {
        return false;
    }

    public function canDecline(OrderStatusContext $orderContext): bool
    {
        return false;
    }

    public function canCancel(OrderStatusContext $orderContext): bool
    {
        return false;
    }
}