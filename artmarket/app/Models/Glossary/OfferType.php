<?php

namespace App\Models\Glossary;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Database\Factories\Glossary\OfferTypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Glossary\OfferType
 *
 * @property int $id
 * @property array $title Offer type title (Translatable)
 * @property array|null $description Description (Translatable)
 * @property string $alias Offer type system unique identification)
 * @property string|null $icon Icon css class
 * @property int $active Offer type active
 * @property int|null $order Offer type sorting
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Category[] $categories
 * @property-read int|null $categories_count
 * @property-read array $translations
 * @property-read Collection|OptionGroup[] $optionGroups
 * @property-read int|null $option_groups_count
 * @method static OfferTypeFactory factory(...$parameters)
 * @method static Builder|OfferType newModelQuery()
 * @method static Builder|OfferType newQuery()
 * @method static Builder|OfferType query()
 * @method static Builder|OfferType whereActive($value)
 * @method static Builder|OfferType whereAlias($value)
 * @method static Builder|OfferType whereCreatedAt($value)
 * @method static Builder|OfferType whereDescription($value)
 * @method static Builder|OfferType whereIcon($value)
 * @method static Builder|OfferType whereId($value)
 * @method static Builder|OfferType whereOrder($value)
 * @method static Builder|OfferType whereTitle($value)
 * @method static Builder|OfferType whereUpdatedAt($value)
 * @mixin Eloquent
 */
class OfferType extends Model
{
    use CrudTrait, hasFactory, HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'glossary_offer_types';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $translatable = ['title', 'description'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getRouteKeyName(): string
    {
        return 'alias';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function optionGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            OptionGroup::class,
            'glossary_option_groups_offer_types',
            'offer_type_id',
            'options_group_id'
        );
    }

    public function categories(): HasMany
    {
        return $this->HasMany(Category::class);
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
