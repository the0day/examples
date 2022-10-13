<?php

namespace App\Models\Glossary;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Glossary\OfferPurpose
 *
 * @property int $id
 * @property int $offer_type_id Offer Type ID
 * @property string $title Title (Translatable)
 * @property string $alias Alias
 * @property string|null $icon Icon css class
 * @property int $active Offer Purpose Status
 * @property int|null $order Offer Purpose Sorting
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read array $translations
 * @property-read OfferType $offerType
 * @method static Builder|OfferPurpose newModelQuery()
 * @method static Builder|OfferPurpose newQuery()
 * @method static Builder|OfferPurpose query()
 * @method static Builder|OfferPurpose whereActive($value)
 * @method static Builder|OfferPurpose whereAlias($value)
 * @method static Builder|OfferPurpose whereCreatedAt($value)
 * @method static Builder|OfferPurpose whereIcon($value)
 * @method static Builder|OfferPurpose whereId($value)
 * @method static Builder|OfferPurpose whereOfferTypeId($value)
 * @method static Builder|OfferPurpose whereOrder($value)
 * @method static Builder|OfferPurpose whereTitle($value)
 * @method static Builder|OfferPurpose whereUpdatedAt($value)
 * @mixin Eloquent
 */
class OfferPurpose extends Model
{
    use CrudTrait, hasFactory, HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'glossary_offer_purposes';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
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
    public function offerType()
    {
        return $this->belongsTo(OfferType::class);
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
    public function getTitleAttribute(): string
    {
        return $this->title;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
