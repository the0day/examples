<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Order $order)
    {
        return $this->buyer($user, $order) || $this->seller($user, $order);
    }

    public function buyer(User $user, Order $order): bool
    {
        return $order->user_id == $user->id;
    }

    public function seller(User $user, Order $order): bool
    {
        return $order->seller_id == $user->id;
    }

    public function accept(User $user, Order $order): bool
    {
        return $this->seller($user, $order) && $order->status->equals(OrderStatus::accepting());
    }

    public function approve(User $user, Order $order): bool
    {
        return $this->buyer($user, $order) && $order->status->equals(OrderStatus::approving());
    }

    public function upload(User $user, Order $order): bool
    {
        return $this->seller($user, $order) && $order->status->equals(OrderStatus::accepted());
    }
}
