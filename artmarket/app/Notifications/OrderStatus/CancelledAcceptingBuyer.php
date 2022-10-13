<?php

namespace App\Notifications\OrderStatus;

use Illuminate\Notifications\Messages\MailMessage;

class CancelledAcceptingBuyer extends OrderStatusNotification
{
    public function toMail()
    {
        return (new MailMessage)
            ->greeting(__('notification.mail.order.cancelled_accepting.buyer.title'))
            ->line(__('notification.mail.order.cancelled_accepting.buyer.body'))
            ->action(__('notification.button.open_order'), route('account.orders.view', $this->order->id));
    }

    public function toArray()
    {
        $order = $this->order;
        $title = __('notification.database.order.cancelled_accepting.buyer.title');
        $message = __('notification.database.order.cancelled_accepting.buyer.title');

        return $this->toOrderArray($order->id, $title, $message, $this->order->offer->getFirstPreview());
    }
}