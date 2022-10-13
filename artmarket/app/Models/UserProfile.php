<?php

namespace App\Models;

use App\Models\Glossary\Country;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserProfile
 *
 * @property int $user_id User ID
 * @property string|null $firstname First name of user
 * @property string|null $lastname Last name of user
 * @property string|null $avatar User avatar path
 * @property string|null $gender M - Male, F - Female
 * @property string|null $birthday Birthday of user
 * @property string|null $about Additional information about user
 * @property string|null $phone Phone number of user
 * @property string|null $tagline Slogan is using in advertising
 * @property int|null $country_id Country ID
 * @property int|null $city_id City ID
 * @property Country|null $country Country
 * @property Country|null $city City
 * @property array|null $social Links for social networks (FB, Twitch, YouTube)
 * @property array|null $languages Language skills
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @method static Builder|UserProfile newModelQuery()
 * @method static Builder|UserProfile newQuery()
 * @method static Builder|UserProfile query()
 * @method static Builder|UserProfile whereAbout($value)
 * @method static Builder|UserProfile whereAvatar($value)
 * @method static Builder|UserProfile whereBirthday($value)
 * @method static Builder|UserProfile whereCity($value)
 * @method static Builder|UserProfile whereCityId($value)
 * @method static Builder|UserProfile whereCountry($value)
 * @method static Builder|UserProfile whereCountryId($value)
 * @method static Builder|UserProfile whereFirstname($value)
 * @method static Builder|UserProfile whereGender($value)
 * @method static Builder|UserProfile whereLanguages($value)
 * @method static Builder|UserProfile whereLastname($value)
 * @method static Builder|UserProfile wherePhone($value)
 * @method static Builder|UserProfile whereSocial($value)
 * @method static Builder|UserProfile whereTagline($value)
 * @method static Builder|UserProfile whereUpdatedAt($value)
 * @method static Builder|UserProfile whereUserId($value)
 * @mixin Eloquent
 */
class UserProfile extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    const CREATED_AT = null;

    protected $table = 'user_profiles';
    protected $primaryKey = 'user_id';
    // public $timestamps = false;
    protected $guarded = ['user_id'];
    protected $fillable = ['social'];
    // protected $hidden = [];// protected $dates = [];

    protected $casts = [
        'social'    => 'json',
        'languages' => 'array'
    ];

    protected $fakeColumns = [
        'social',
        'languages'
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(Country::class);
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
    public function getAvatarSrc(): string
    {
        return 'https://images.unsplash.com/photo-1550525811-e5869dd03032?ixlib=rb-1.2.1&ixqx=7qwKjEp7Xv&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80';
    }

    public function getProfileFields(): array
    {
        return ['countru', 'city', 'languages'];
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
