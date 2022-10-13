<?php

namespace App\Models;

use App\Enums\OptionFieldType;
use App\Models\Glossary\Option;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Cknow\Money\Casts\MoneyIntegerCast;
use Cknow\Money\Money;
use Database\Factories\OfferOptionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Enum\Enum;

/**
 * App\Models\OfferOption
 *
 * @property int $id
 * @property int|null $option_id Option ID
 * @property int $offer_id Offer ID
 * @property array|null $name Option name (Translatable)
 * @property Enum|null $field_type 1 - text, 2 - number, 3 - radio, 4 - select, 5 - checkbox
 * @property array|null $field_values Option Value
 * @property int|null $days Days
 * @property Money|null|null $price Option price
 * @property string $currency Option price currency
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read array $translations
 * @property-read Option|null $glossary
 * @property-read Offer $offer
 * @method static Builder|OfferOption newModelQuery()
 * @method static Builder|OfferOption newQuery()
 * @method static Builder|OfferOption query()
 * @method static Builder|OfferOption whereCreatedAt($value)
 * @method static Builder|OfferOption whereCurrency($value)
 * @method static Builder|OfferOption whereDays($value)
 * @method static Builder|OfferOption whereFieldValues($value)
 * @method static Builder|OfferOption whereId($value)
 * @method static Builder|OfferOption whereName($value)
 * @method static Builder|OfferOption whereOfferId($value)
 * @method static Builder|OfferOption whereOptionId($value)
 * @method static Builder|OfferOption wherePrice($value)
 * @method static Builder|OfferOption whereUpdatedAt($value)
 * @method static Builder|OfferOption whereFieldType($value)
 * @method static OfferOptionFactory factory(...$parameters)
 * @mixin Eloquent
 */
class OfferOption extends Model
{
    use CrudTrait, HasFactory, HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'offer_options';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['name', 'offer_id', 'field_values', 'days', 'price'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $translatable = ['name'];

    protected $casts = [
        'field_type'   => OptionFieldType::class,
        'field_values' => 'array',
        'price'        => MoneyIntegerCast::class
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
    public function glossary(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'option_id', 'id');
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class, 'offer_id', 'id');
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
    public function getValue(): ?string
    {
        return $this->getFieldValue(app()->getLocale()) ?? null;
    }

    public function getDays(string $subOption = null): ?int
    {
        if ($subOption && $subOption != 0) {
            return $this->getFieldValue($subOption)['days'] ?? null;
        }

        return $this->days;
    }

    public function getPrice(string $subOption = null): ?Money
    {
        if ($subOption && $subOption != 0) {
            $field = $this->getFieldValue($subOption);

            return isset($field['price'])
                ? money($field['price'], $this->currency)
                : null;
        }


        return $this->price;
    }

    private function getFieldValue(string $key)
    {
        return $this->field_values[$key] ?? null;
    }

    public function getFieldsForSelect(): array
    {
        $list = ['' => '-'];
        foreach ($this->field_values as $field => $data) {
            $list[$field] = $this->glossary->getFieldTitle($field) . " (+{$this->getPrice($field)}, {$this->getDays($field)} " . __("offer_option.text.days_short") . ")";
        }

        return $list;
    }

    public function isOrderUpgrade(): bool
    {
        if ($this->field_type->isSelector()) {
            return true;
        }

        if (!$this->days && !$this->price) {
            return false;
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
