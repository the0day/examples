<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserNotification
 *
 * @property int $id
 * @property int $user_id User ID
 * @property string|null $icon Notification icon
 * @property string|null $message Notification body
 * @property string|null $channel Notification channel (news, chat, new order)
 * @property mixed|null $params Notification channel (news, chat, new order)
 * @property string|null $model_type Related model (for generation link). Example: App\Models\User
 * @property string|null $model_id Related model ID. Example: 1
 * @property int $has_read Set to true after read a notification
 * @property string|null $read_at When it sets is_read to true, it also sets readed time
 * @property Carbon $updated_at
 * @method static Builder|UserNotification newModelQuery()
 * @method static Builder|UserNotification newQuery()
 * @method static Builder|UserNotification query()
 * @method static Builder|UserNotification whereChannel($value)
 * @method static Builder|UserNotification whereHasRead($value)
 * @method static Builder|UserNotification whereIcon($value)
 * @method static Builder|UserNotification whereId($value)
 * @method static Builder|UserNotification whereMessage($value)
 * @method static Builder|UserNotification whereModelId($value)
 * @method static Builder|UserNotification whereModelType($value)
 * @method static Builder|UserNotification whereParams($value)
 * @method static Builder|UserNotification whereReadAt($value)
 * @method static Builder|UserNotification whereUpdatedAt($value)
 * @method static Builder|UserNotification whereUserId($value)
 * @mixin Eloquent
 */
class UserNotification extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'user_notifications';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
