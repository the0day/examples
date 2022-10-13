<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;

/**
 * App\Models\Notification
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property Carbon|null $read_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $notifiable
 * @method static DatabaseNotificationCollection|static[] all($columns = ['*'])
 * @method static DatabaseNotificationCollection|static[] get($columns = ['*'])
 * @method static Builder|Notification newModelQuery()
 * @method static Builder|Notification newQuery()
 * @method static Builder|Notification query()
 * @method static Builder|DatabaseNotification read()
 * @method static Builder|DatabaseNotification unread()
 * @method static Builder|Notification whereCreatedAt($value)
 * @method static Builder|Notification whereData($value)
 * @method static Builder|Notification whereId($value)
 * @method static Builder|Notification whereNotifiableId($value)
 * @method static Builder|Notification whereNotifiableType($value)
 * @method static Builder|Notification whereReadAt($value)
 * @method static Builder|Notification whereType($value)
 * @method static Builder|Notification whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Notification extends DatabaseNotification
{
    public function link(): ?string
    {
        $data = $this->data;

        if (isset($data['url'])) {
            return $data['url'];
        }

        if (isset($data['order_id'])) {
            return route('account.orders.view', $data['order_id']);
        }

        return null;
    }
}