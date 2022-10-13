<?php

namespace App\Services\Order\Statuses;

use App\Services\Order\OrderStatusContext;

/**
 * The final status of cancelled order
 * also @see Delivered (another final status)
 */
class Cancelled extends Status
{
    public function authorized(OrderStatusContext $orderContext): bool
    {
        return false;
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

    public function accept(OrderStatusContext $orderContext): void
    {

    }

    public function decline(OrderStatusContext $orderContext): void
    {

    }

    public function cancel(OrderStatusContext $orderContext): void
    {

    }
}