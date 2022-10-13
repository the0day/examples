<?php

namespace App\Services\Order\Statuses;

use App\Enums\OrderStatus;
use App\Models\User;
use App\Services\Order\OrderStatusContext;
use Gate;

abstract class Status
{
    /**
     * Determine if the given ability should be granted for the current user.
     *
     * @param User $user
     * @param $abilities
     * @param mixed|array $arguments
     * @return bool
     */
    public function gate(User $user, $abilities, $arguments = []): bool
    {
        return Gate::forUser($user)->allows($abilities, $arguments);
    }

    protected function applyStatus(OrderStatusContext $orderContext, OrderStatus $newStatus): void
    {
        $order = $orderContext->getOrder();
        $order->status = $newStatus;

        if ($newStatus == OrderStatus::cancelling()) {
            $order->cancelled_by_id = $orderContext->getUser()->id;
        }
        $order->save();
    }

    abstract public function authorized(OrderStatusContext $orderContext): bool;

    abstract public function canAccept(OrderStatusContext $orderContext): bool;

    abstract public function canDecline(OrderStatusContext $orderContext): bool;

    abstract public function canCancel(OrderStatusContext $orderContext): bool;

    abstract public function accept(OrderStatusContext $orderContext): void;

    abstract public function decline(OrderStatusContext $orderContext): void;

    abstract public function cancel(OrderStatusContext $orderContext): void;
}