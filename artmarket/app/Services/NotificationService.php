<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class NotificationService
{
    public function deleteNotificationsFromChat(User $user, Order $order)
    {
        $removeIds = [];
        $notifications = $user->unreadNotifications();
        foreach ($notifications->get() as $notification) {
            $orderId = $notification->data['order_id'] ?? null;
            if ($order->id != $orderId) {
                continue;
            }
            $removeIds[] = $notification->id;
        }
        $notifications->whereIn('id', $removeIds)->delete();
    }
}