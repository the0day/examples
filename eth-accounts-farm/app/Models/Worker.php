<?php

namespace App\Models;

use App\Enums\RoleEnum;
use App\Enums\WorkerStatusEnum;
use App\Settings\GeneralSettings;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Worker
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $operator_id ID Оператора
 * @property WorkerStatusEnum $status (0 - свободен, 1 - отключен вручную, 2 - отключен из-за баланса ниже нормы, 3 - занят задачей)
 * @property string $address Адрес кошелька, который соответствует воркеру
 * @property string $private_key Приватный ключ кошелька для подписи транзакций
 * @property int $role_id 0 - обычный воркер, 1 - админ/менеджер, остальные коды - кастомные, настраиваются в конфиге, пока не используются
 * @method static Builder|Worker newModelQuery()
 * @method static Builder|Worker newQuery()
 * @method static Builder|Worker query()
 * @method static Builder|Worker whereAddress($value)
 * @method static Builder|Worker whereCreatedAt($value)
 * @method static Builder|Worker whereId($value)
 * @method static Builder|Worker whereOperatorId($value)
 * @method static Builder|Worker wherePrivateKey($value)
 * @method static Builder|Worker whereRoleId($value)
 * @method static Builder|Worker whereStatus($value)
 * @method static Builder|Worker whereUpdatedAt($value)
 * @method static Builder|Worker byOperator(int $operatorId)
 * @method static Builder|Worker byStatus(WorkerStatusEnum $status)
 * @method static Builder|Worker byAddressOrId(string $search)
 * @mixin Eloquent
 * @property-read Operator $operator
 * @property-read Collection|Task[] $tasks
 * @property-read int|null $tasks_count
 * @method static Builder|Worker byRole(RoleEnum $role)
 * @property float|null $balance
 * @method static Builder|Worker whereBalance($value)
 */
class Worker extends Model
{
    use HasFactory;

    protected $casts = [
        'status'  => WorkerStatusEnum::class,
        'role_id' => RoleEnum::class,
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function scopeByOperator(Builder $query, int $operatorId): Builder
    {
        return $query->where('operator_id', $operatorId);
    }

    public function scopeByStatus(Builder $query, WorkerStatusEnum $status): Builder
    {
        return $query->where('status', $status->value);
    }

    public function scopeByRole(Builder $query, RoleEnum $role): Builder
    {
        return $query->where('role_id', $role->value);
    }

    public function scopeByAddressOrId(Builder $query, string $search): Builder
    {
        return $query->where(function ($query) use ($search) {
            $query->where('id', $search)
                ->orWhere('address', $search);
        });
    }

    public function hasEnoughBalance(): bool
    {
        $minBalance = app(GeneralSettings::class)->min_balance;
        return $minBalance < $this->balance;
    }

    public function canWork(): bool
    {
        return !$this->status->equals(WorkerStatusEnum::available());
    }
}
