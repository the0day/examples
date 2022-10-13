<?php

namespace App\Models;

use App\DTO\ImageMeta;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Upload
 *
 * @property int $id
 * @property int $user_id User ID
 * @property int|null $offer_id Offer ID
 * @property int|null $subject
 * @property string $name
 * @property string $path
 * @property string $mimetype
 * @property int $filesize
 * @property mixed|null $meta
 * @property string|null $created_at
 * @property string|null $deleted_at
 * @property-read Offer|null $offer
 * @property-read User $user
 * @method static Builder|Upload newModelQuery()
 * @method static Builder|Upload newQuery()
 * @method static Builder|Upload query()
 * @method static Builder|Upload whereCreatedAt($value)
 * @method static Builder|Upload whereDeletedAt($value)
 * @method static Builder|Upload whereFilesize($value)
 * @method static Builder|Upload whereId($value)
 * @method static Builder|Upload whereMeta($value)
 * @method static Builder|Upload whereMimetype($value)
 * @method static Builder|Upload whereName($value)
 * @method static Builder|Upload whereOfferId($value)
 * @method static Builder|Upload wherePath($value)
 * @method static Builder|Upload whereSubject($value)
 * @method static Builder|Upload whereUserId($value)
 * @mixin Eloquent
 */
class Upload extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'uploads';
    // protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
        'meta' => ImageMeta::class,
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
