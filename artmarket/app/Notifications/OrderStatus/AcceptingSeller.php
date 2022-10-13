<?php

namespace App\Notifications\OrderStatus;

use Illuminate\Notifications\Messages\MailMessage;

class AcceptingSeller extends OrderStatusNotification
{
    public function toMail()
    {
        return (new MailMessage)
            ->greeting(__('notification.mail.order.accepting.seller.title'))
            ->line(__('notification.mail.order.accepting.seller.body'))
            ->action(__('notification.button.open_order'), route('account.orders.view', $this->order->id));
    }

    public function toArray()
    {
        $order = $this->order;
        $title = __('notification.database.order.accepting.seller.title');
        $message = __('notification.database.order.accepting.seller.title');

        return $this->toOrderArray($order->id, $title, $message, $this->order->offer->getFirstPreview());
    }
}
