<?php

namespace App\Models\Glossary;

use App\Enums\OptionFieldType;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Database\Factories\Glossary\OptionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Enum\Enum;


/**
 * App\Models\Glossary\Option
 *
 * @property int $id
 * @property int $group_id Options Group ID
 * @property string $title Option title (Translatable)
 * @property array|null $description Option description (Translatable)
 * @property string $alias Option system unique identification)
 * @property string|null $icon Option icon class
 * @property int $price Option costs
 * @property string $currency Option price currency
 * @property Enum|null $field_type 1 - text, 2 - number, 3 - radio, 4 - select, 5 - checkbox
 * @property mixed|null $field_values Predefined values for radio and select
 * @property int $active Option active
 * @property int|null $order Option sorting
 * @property mixed|null $measure_unit Option measure unit (Translatable
 * @property int|null $extra_days Extra days
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read array $translations
 * @property-read array $values
 * @property-read OptionGroup|null $group
 * @method static OptionFactory factory(...$parameters)
 * @method static Builder|Option newModelQuery()
 * @method static Builder|Option newQuery()
 * @method static Builder|Option query()
 * @method static Builder|Option whereActive($value)
 * @method static Builder|Option whereAlias($value)
 * @method static Builder|Option whereCreatedAt($value)
 * @method static Builder|Option whereCurrency($value)
 * @method static Builder|Option whereDescription($value)
 * @method static Builder|Option whereExtraDays($value)
 * @method static Builder|Option whereFieldType($value)
 * @method static Builder|Option whereFieldValues($value)
 * @method static Builder|Option whereGroupId($value)
 * @method static Builder|Option whereIcon($value)
 * @method static Builder|Option whereId($value)
 * @method static Builder|Option whereMeasureUnit($value)
 * @method static Builder|Option whereOrder($value)
 * @method static Builder|Option wherePrice($value)
 * @method static Builder|Option whereTitle($value)
 * @method static Builder|Option whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Option extends Model
{
    use CrudTrait, hasFactory, HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'glossary_options';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $translatable = ['title', 'description'];

    protected $casts = [
        'field_type'   => OptionFieldType::class,
        'field_values' => 'array'
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
    public function group()
    {
        return $this->belongsTo(OptionGroup::class, 'group_id', 'id');
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

    public function hasFieldValues(): bool
    {
        return is_array($this->field_values) && count($this->field_values) > 0;
    }

    public function getFieldTitle(string $key): ?string
    {
        $values = $this->field_values;
        $transKey = 'glossary_option.name' . $key;
        if (__($transKey) !== $transKey) {
            return __($transKey);
        }

        return $values[$key] ?? $key;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
