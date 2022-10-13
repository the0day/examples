<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Cknow\Money\Casts\MoneyIntegerCast;
use Cknow\Money\Money;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property int $payment_method_id Payment method ID
 * @property int $user_id User ID
 * @property int|null $order_id Order ID
 * @property int|null $offer_id Offer ID
 * @property string|null $status
 * @property Carbon $created_at
 * @property Money|null $amount Amount
 * @property string $currency Currency
 * @property-read Offer|null $offer
 * @property-read Order|null $order
 * @property-read PaymentMethod $paymentMethod
 * @property-read User $user
 * @method static Builder|Payment newModelQuery()
 * @method static Builder|Payment newQuery()
 * @method static Builder|Payment query()
 * @method static Builder|Payment whereAmount($value)
 * @method static Builder|Payment whereCreatedAt($value)
 * @method static Builder|Payment whereCurrency($value)
 * @method static Builder|Payment whereId($value)
 * @method static Builder|Payment whereOfferId($value)
 * @method static Builder|Payment whereOrderId($value)
 * @method static Builder|Payment wherePaymentMethodId($value)
 * @method static Builder|Payment whereStatus($value)
 * @method static Builder|Payment whereUserId($value)
 * @mixin Eloquent
 * @property Carbon $updated_at
 * @method static Builder|Payment whereUpdatedAt($value)
 */
class Payment extends Model
{
    use CrudTrait, hasFactory;

    protected $table = 'payments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    //protected $fillable = ['total_cost'];
    // protected $hidden = [];
    protected $dates = ['created_at'];

    protected $casts = [
        'amount' => MoneyIntegerCast::class . ':currency',
    ];

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
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

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
