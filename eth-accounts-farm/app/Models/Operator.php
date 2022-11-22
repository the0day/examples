<?php

namespace App\Models;

use App\Enums\RoleEnum;
use App\Enums\WorkerStatusEnum;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Enum\Enum;

/**
 * App\Models\Operator
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $chain_id
 * @property string $label
 * @property string $key
 * @property string $task_callback
 * @property string $status_callback
 * @method static Builder|Operator newModelQuery()
 * @method static Builder|Operator newQuery()
 * @method static Builder|Operator query()
 * @method static Builder|Operator whereChainId($value)
 * @method static Builder|Operator whereCreatedAt($value)
 * @method static Builder|Operator whereId($value)
 * @method static Builder|Operator whereKey($value)
 * @method static Builder|Operator whereLabel($value)
 * @method static Builder|Operator whereStatusCallback($value)
 * @method static Builder|Operator whereTaskCallback($value)
 * @method static Builder|Operator whereUpdatedAt($value)
 * @mixin Eloquent
 * @property Enum|null $status
 * @property Enum|null $role_id
 * @property-read Collection|Task[] $tasks
 * @property-read int|null $tasks_count
 * @property-read Collection|Worker[] $workers
 * @property-read int|null $workers_count
 */
class Operator extends Model
{
    use HasFactory;

    protected $casts = [
        'status'  => WorkerStatusEnum::class,
        'role_id' => RoleEnum::class,
    ];

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
