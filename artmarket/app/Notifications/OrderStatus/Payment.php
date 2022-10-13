<?php

namespace App\Notifications\OrderStatus;

use Illuminate\Notifications\Messages\MailMessage;

class Payment extends OrderStatusNotification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting(__('notification.mail.order.payment.title'))
            ->line(__('notification.mail.order.payment.body'))
            ->action(__('notification.button.open_order'), route('account.orders.view', $this->order->id));
    }

    public function toArray($notifiable)
    {
        $order = $this->order;
        $title = __('notification.database.order.payment.title');
        $message = __('notification.database.order.payment.title');

        return $this->toOrderArray($order->id, $title, $message, $this->order->offer->getFirstPreview());
    }
}
