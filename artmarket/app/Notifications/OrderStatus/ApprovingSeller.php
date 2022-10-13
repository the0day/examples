<?php

namespace App\Notifications\OrderStatus;

use Illuminate\Notifications\Messages\MailMessage;

class ApprovingSeller extends OrderStatusNotification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting(__('notification.mail.order.approving.seller.title'))
            ->line(__('notification.mail.order.approving.seller.body'))
            ->action(__('notification.button.open_order'), route('account.orders.view', $this->order->id));
    }

    public function toArray($notifiable)
    {
        $order = $this->order;
        $title = __('notification.database.order.approving.seller.title');
        $message = __('notification.database.order.approving.seller.title');

        return $this->toOrderArray($order->id, $title, $message, $this->order->offer->getFirstPreview());
    }
}
