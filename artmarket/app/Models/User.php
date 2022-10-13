<?php

namespace App\Models;

use App\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Cknow\Money\Casts\MoneyIntegerCast;
use Cknow\Money\Money;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $last_seen_at
 * @property Money|null $credit
 * @property string $currency Currency
 * @property-read UserProfile|null $profile
 * @property-read int|null $offers_count
 * @property-read int|null $uploads_count
 * @property-read int|null $orders_count
 * @property-read int|null $notifications_count
 * @property-read Collection|Offer[] $offers
 * @property-read Collection|Upload[] $uploads
 * @property-read Collection|Order[] $orders
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereCredit($value)
 * @method static Builder|User whereCurrency($value)
 * @method static Builder|User whereLastSeenAt($value)
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use CrudTrait, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $visible = [
        'id',
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'credit'            => MoneyIntegerCast::class . ':currency',
    ];

    protected $dates = ['created_at', 'updated_at', 'last_seen_at'];

    public function getRouteKeyName(): string
    {
        return 'name';
    }

    /**
     * Profile info (firstname, lastname, etc...)
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Offer list
     * @return HasMany
     */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Orders
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Uploaded files
     * @return HasMany
     */
    public function uploads(): HasMany
    {
        return $this->hasMany(Upload::class);
    }

    public function hasEnoughCredit(Money $need): bool
    {
        return $this->credit->greaterThanOrEqual($need);
    }

    public function toChatArray(): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'avatar'       => $this->profile->avatar,
            'online'       => $this->isOnline(),
            'last_seen_at' => $this->getLastOnline()
        ];
    }

    public function getLastOnline(): ?string
    {
        return $this->last_seen_at?->diffForHumans();

    }

    public function isOnline(): bool
    {
        if (!$this->last_seen_at) {
            return false;
        }

        return $this->last_seen_at->greaterThan(Carbon::now()->sub('15 minutes'));
    }
}
