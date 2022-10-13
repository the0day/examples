<?php

namespace App\Models;

use App\Enums\MediaCollectionType;
use App\Enums\MediaConversion;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Database\Factories\MessageFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

/**
 * App\Models\Message
 *
 * @property int $id
 * @property int $order_id
 * @property int $user_id
 * @property string $body
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read int|null $uploads_count
 * @property-read Collection|Upload[] $uploads
 * @property-read User $user
 * @property-read Order $order
 * @method static MessageFactory factory(...$parameters)
 * @method static Builder|Message newModelQuery()
 * @method static Builder|Message newQuery()
 * @method static Builder|Message query()
 * @method static Builder|Message whereBody($value)
 * @method static Builder|Message whereCreatedAt($value)
 * @method static Builder|Message whereDeletedAt($value)
 * @method static Builder|Message whereId($value)
 * @method static Builder|Message whereOrderId($value)
 * @method static Builder|Message whereUpdatedAt($value)
 * @method static Builder|Message whereUserId($value)
 * @mixin Eloquent
 */
class Message extends Model implements HasMedia
{
    use CrudTrait, hasFactory, InteractsWithMedia;

    protected $table = 'messages';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['updated_at'];
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = ['body', 'user_id', 'order_id'];
    protected $casts = [];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function uploads(): HasMany
    {
        return $this->hasMany(
            Upload::class,
        );
    }

    public function getImages(): MediaCollection
    {
        return $this->getMedia(MediaCollectionType::message());
    }

    public function registerMediaConversions(BaseMedia $media = null): void
    {
        $this->addMediaConversion(MediaConversion::thumb())
            ->width(350)
            ->height(350);
    }

    public function toArray()
    {
        return [
            'id'           => $this->id,
            'user_id'      => $this->user_id,
            'body'         => $this->body,
            'order_id'     => $this->order_id,
            'images'       => $this->getImages()->toArray(),
            'published_at' => $this->created_at->diffForHumans(),
            'user'         => $this->user->toChatArray(),
        ];
    }
}