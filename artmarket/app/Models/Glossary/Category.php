<?php

namespace App\Models\Glossary;

use App\Models\Offer;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Database\Factories\Glossary\CategoryFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Glossary\Category
 *
 * @property int $id
 * @property int $offer_type_id Offer Type ID
 * @property int|null $parent_id Parent Category ID
 * @property string $title Category title (Translatable)
 * @property string|null $icon Icon css class
 * @property string $alias Category unique identification)
 * @property int $active Category status
 * @property int|null $order Category sorting
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Offer[] $categories
 * @property-read int|null $categories_count
 * @property-read Collection|Category[] $childrens
 * @property-read int|null $childrens_count
 * @property-read array $translations
 * @property-read OfferType $offerType
 * @property-read Category|null $parent
 * @method static CategoryFactory factory(...$parameters)
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereActive($value)
 * @method static Builder|Category whereAlias($value)
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereIcon($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereOfferTypeId($value)
 * @method static Builder|Category whereOrder($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category whereTitle($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Category extends Model
{
    use CrudTrait, hasFactory, HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'glossary_categories';
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
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function childrens(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function offerType(): BelongsTo
    {
        return $this->belongsTo(OfferType::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class,
            'offer_categories',
            'category_id',
            'offer_id');
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
