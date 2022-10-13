<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserAuthorization
 *
 * @property int $id
 * @property int $user_id User ID
 * @property string $ip IP which was used for login
 * @property int $is_successful True if sign in was successful
 * @property Carbon $updated_at
 * @method static Builder|UserAuthorization newModelQuery()
 * @method static Builder|UserAuthorization newQuery()
 * @method static Builder|UserAuthorization query()
 * @method static Builder|UserAuthorization whereId($value)
 * @method static Builder|UserAuthorization whereIp($value)
 * @method static Builder|UserAuthorization whereIsSuccessful($value)
 * @method static Builder|UserAuthorization whereUpdatedAt($value)
 * @method static Builder|UserAuthorization whereUserId($value)
 * @mixin Eloquent
 */
class UserAuthorization extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'user_authorizations';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
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
