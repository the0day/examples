<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Review
 *
 * @property int $id
 * @property int $order_id Order ID
 * @property int $offer_id Offer ID
 * @property int $user_id User ID
 * @property int $seller_id Seller ID
 * @property int $rate Integer value between 1 and 5
 * @property string $comment Review text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Review newModelQuery()
 * @method static Builder|Review newQuery()
 * @method static Builder|Review query()
 * @method static Builder|Review whereComment($value)
 * @method static Builder|Review whereCreatedAt($value)
 * @method static Builder|Review whereId($value)
 * @method static Builder|Review whereOfferId($value)
 * @method static Builder|Review whereOrderId($value)
 * @method static Builder|Review whereRate($value)
 * @method static Builder|Review whereSellerId($value)
 * @method static Builder|Review whereUpdatedAt($value)
 * @method static Builder|Review whereUserId($value)
 * @mixin Eloquent
 */
class Review extends Model
{
    use CrudTrait;

    protected $table = 'reviews';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    //protected $fillable = ['total_cost'];
    // protected $hidden = [];
    protected $dates = ['created_at', 'updated_at'];
}