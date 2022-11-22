<?php

namespace App\Models;

use App\Enums\RoleEnum;
use App\Enums\TaskStatusEnum;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Web3\Utils;

/**
 * App\Models\Task
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $worker_id
 * @property int $operator_id
 * @property string $tx_to Получатель транзакции
 * @property int $tx_value Количество отправляемого эфир    а
 * @property string $tx_data данные (комментарий) к транзакции
 * @property string $tx_hash
 * @property int $status 0 - воркер не назначен, 1 - воркер назначен, 2 - произошел reject (web3->send завершился ошибкой), 3 - транзакция принята, получен хеш, ожидание включения в блок, 4 - транзакция включена в блок, но был revert (ошибка при исполнении алгоритма), 5 - транзакция включена в блок с успешным статусом и достаточным кол-вом подтверждений
 * @property string $registered_at Время регистрации задания
 * @property string|null $executed_at время получения результата (реджект, реверт, успех)
 * @property string $response данные, возникшие при исполнении транзакции
 * @property string $post_at время отложенного исполнения (задание назначается воркеру только после указанного времени)
 * @property string $post_at_node
 * @property RoleEnum $role_id какая роль должна быть у свободного воркера для назначения такой задачи
 * @property int $priority вес, по которому сортируются задания для выполнения воркерами
 * @property string $uuid uuid транзакции - уникальный символьный ключ транзакции, по которому возможно обращение извне
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static Builder|Task query()
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereExecutedAt($value)
 * @method static Builder|Task whereId($value)
 * @method static Builder|Task whereOperatorId($value)
 * @method static Builder|Task wherePostAt($value)
 * @method static Builder|Task wherePostAtNode($value)
 * @method static Builder|Task wherePriority($value)
 * @method static Builder|Task whereRegisteredAt($value)
 * @method static Builder|Task whereResponse($value)
 * @method static Builder|Task whereRoleId($value)
 * @method static Builder|Task whereStatus($value)
 * @method static Builder|Task whereUuid($value)
 * @method static Builder|Task whereTxData($value)
 * @method static Builder|Task whereTxHash($value)
 * @method static Builder|Task whereTxTo($value)
 * @method static Builder|Task whereTxValue($value)
 * @method static Builder|Task whereUpdatedAt($value)
 * @method static Builder|Task whereWorkerId($value)
 * @method static Builder|Task withoutWorker()
 * @method static Builder|Task byStatus(TaskStatusEnum $status)
 * @property-read Operator $operator
 * @property-read Worker|null $worker
 * @mixin Eloquent
 * @property int|null $chain_id
 * @property int|null $block_id
 * @method static Builder|Task whereBlockId($value)
 * @method static Builder|Task whereChainId($value)
 */
class Task extends Model
{
    use HasFactory;

    protected $casts = [
        'status'        => TaskStatusEnum::class,
        'role_id'       => RoleEnum::class,
        'registered_at' => 'datetime',
        'post_at'       => 'datetime',
    ];

    public function worker(): HasOne
    {
        return $this->hasOne(Worker::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function scopeWithoutWorker(Builder $query): Builder
    {
        return $query->whereNull('worker_id');
    }

    public function scopeByStatus(Builder $query, TaskStatusEnum $status): Builder
    {
        return $query->where('status', $status->value);
    }

    public function getTransactionData(): array
    {
        return [
            //'nonce'    => Utils::toHex(1, true),
            'from'    => '0x' . $this->worker->address,
            'to'      => $this->tx_to,
            //'gas'      => Utils::toHex(2000000, true),
            //'gasPrice' => (string) Infura::getGasPrice(),
            'value'   => Utils::toHex($this->tx_value, true),
            'chainId' => $this->chain_id,
            'data'    => $this->tx_data
        ];
    }

    public function hasTxHash(): bool
    {
        return !empty($this->tx_hash);
    }
}
