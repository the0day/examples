<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PaymentMethod
 *
 * @property int $id
 * @property string $name
 * @property array|null $title
 * @property int|null $order Payment methods ordering
 * @property int $active Active payment method
 * @property float $commission Commission
 * @property-read array $translations
 * @method static Builder|PaymentMethod active()
 * @method static Builder|PaymentMethod newModelQuery()
 * @method static Builder|PaymentMethod newQuery()
 * @method static Builder|PaymentMethod query()
 * @method static Builder|PaymentMethod whereActive($value)
 * @method static Builder|PaymentMethod whereCommission($value)
 * @method static Builder|PaymentMethod whereId($value)
 * @method static Builder|PaymentMethod whereName($value)
 * @method static Builder|PaymentMethod whereOrder($value)
 * @method static Builder|PaymentMethod whereTitle($value)
 * @mixin Eloquent
 */
class PaymentMethod extends Model
{
    use CrudTrait, hasFactory, HasTranslations;

    protected $table = 'payment_methods';
    // protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = ['id'];
    //protected $fillable = ['total_cost'];
    // protected $hidden = [];


    protected $casts = [];
    protected $translatable = ['title'];

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
    /**
     * Заказы требующие оплаты
     *
     * @param $query
     * @return void
     */
    public function scopeActive($query)
    {
        $query->where('active', 1);
    }

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
