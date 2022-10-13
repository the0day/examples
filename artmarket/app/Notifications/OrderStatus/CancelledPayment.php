<?php

namespace App\Notifications\OrderStatus;

use Illuminate\Notifications\Messages\MailMessage;

class CancelledPayment extends OrderStatusNotification
{
    public function toMail()
    {
        return (new MailMessage)
            ->greeting(__('notification.mail.order.cancelled_payment.title'))
            ->line(__('notification.mail.order.cancelled_payment.body'))
            ->action(__('notification.button.open_order'), route('account.orders.view', $this->order->id));
    }

    public function toArray()
    {
        $order = $this->order;
        $title = __('notification.database.order.cancelled_payment.title');
        $message = __('notification.database.order.cancelled_payment.title');

        return $this->toOrderArray($order->id, $title, $message, $this->order->offer->getFirstPreview());
    }
}