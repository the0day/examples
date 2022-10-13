<?php

namespace App\Models\Glossary;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\Glossary\CountryFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Glossary\Country
 *
 * @property int $id
 * @property string $name
 * @property string|null $iso3
 * @property string|null $iso2
 * @property string|null $phone_code
 * @property string|null $capital
 * @property string|null $currency
 * @property-read Collection|City[] $cities
 * @property-read int|null $cities_count
 * @method static CountryFactory factory(...$parameters)
 * @method static Builder|Country newModelQuery()
 * @method static Builder|Country newQuery()
 * @method static Builder|Country query()
 * @method static Builder|Country whereCapital($value)
 * @method static Builder|Country whereCurrency($value)
 * @method static Builder|Country whereId($value)
 * @method static Builder|Country whereIso2($value)
 * @method static Builder|Country whereIso3($value)
 * @method static Builder|Country whereName($value)
 * @method static Builder|Country wherePhoneCode($value)
 * @mixin Eloquent
 */
class Country extends Model
{
    use CrudTrait, hasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'countries';
    // protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

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
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
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
