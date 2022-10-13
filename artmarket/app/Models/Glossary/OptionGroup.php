<?php

namespace App\Models\Glossary;

use App\Enums\OptionGroupType;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Database\Factories\Glossary\OptionGroupFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Enum\Enum;

/**
 * App\Models\Glossary\OptionGroup
 *
 * @property int $id
 * @property array $title Title (Translatable)
 * @property array|null $description Description (Translatable)
 * @property string $alias System unique identification)
 * @property Enum|null $type Type of group options
 * @property string|null $icon Icon css class
 * @property int $active Option active
 * @property int|null $order Option sorting
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read array $translations
 * @property-read Collection|OfferType[] $offerTypes
 * @property-read int|null $offer_types_count
 * @property-read Collection|Option[] $options
 * @property-read int|null $options_count
 * @method static OptionGroupFactory factory(...$parameters)
 * @method static Builder|OptionGroup newModelQuery()
 * @method static Builder|OptionGroup newQuery()
 * @method static Builder|OptionGroup query()
 * @method static Builder|OptionGroup whereActive($value)
 * @method static Builder|OptionGroup whereAlias($value)
 * @method static Builder|OptionGroup whereCreatedAt($value)
 * @method static Builder|OptionGroup whereDescription($value)
 * @method static Builder|OptionGroup whereIcon($value)
 * @method static Builder|OptionGroup whereId($value)
 * @method static Builder|OptionGroup whereOrder($value)
 * @method static Builder|OptionGroup whereTitle($value)
 * @method static Builder|OptionGroup whereType($value)
 * @method static Builder|OptionGroup whereUpdatedAt($value)
 * @mixin Eloquent
 */
class OptionGroup extends Model
{
    use CrudTrait, hasFactory, HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'glossary_option_groups';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $translatable = ['title', 'description'];

    protected $casts = [
        'type' => OptionGroupType::class
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
    public function options()
    {
        return $this->hasMany(Option::class, 'group_id');
    }

    public function offerTypes()
    {
        return $this->belongsToMany(
            OfferType::class,
            'glossary_option_groups_offer_types',
            'options_group_id',
            'offer_type_id'
        );
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
