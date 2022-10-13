<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification as BaseNotification;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use Str;

class Notification extends BaseNotification
{
    /**
     * @param int $orderId
     * @param string $title
     * @param string $text
     * @param BaseMedia|null $icon
     * @param array $params
     * @return array
     */
    public function toOrderArray(int $orderId, string $title, string $text, ?BaseMedia $icon, array $params = []): array
    {
        $data = [
            'order_id' => $orderId,
            'title'    => $title,
            'text'     => Str::limit($text, 75),
        ];

        if ($icon) {
            $data['icon'] = $icon->getUrl();
        }

        return array_merge($data, $params);
    }
}