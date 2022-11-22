<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Enums\WorkerStatusEnum;
use App\Jobs\BalanceReceiver;
use App\Models\Operator;
use App\Models\Worker;
use Illuminate\Support\Collection;
use kornrunner\Ethereum\Address;

class WorkerService
{
    /**
     * Создаем воркера
     * @param Operator $operator
     * @param RoleEnum $role
     * @param string|null $privateKey
     * @return Worker
     */
    public function create(Operator $operator, RoleEnum $role, ?string $privateKey = null): Worker
    {
        $address = new Address($privateKey ?? '');

        $worker = new Worker();
        $worker->operator_id = $operator->id;
        $worker->status = WorkerStatusEnum::available();
        $worker->address = $address->get();
        $worker->private_key = $address->getPrivateKey();
        $worker->role_id = $role->value;
        $worker->save();

        return $worker;
    }

    /**
     * Создать несколько воркеров под оператором
     * @param Operator $operator
     * @param RoleEnum $role
     * @param int $count
     * @param array $privateKeys
     * @return Worker[]
     */
    public function createBunch(Operator $operator, RoleEnum $role, int $count = 1, array $privateKeys = []): array
    {
        $result = [];
        $privateKeys = array_values($privateKeys);
        for ($i = 0; $i < $count; $i++) {
            $result[] = $this->create($operator, $role, $privateKeys[$i] ?? null);
        }

        return $result;
    }

    /**
     * Изменить статус воркера
     * @param Worker $worker
     * @param WorkerStatusEnum $status
     * @param bool $save
     * @return void
     */
    public function changeStatus(Worker $worker, WorkerStatusEnum $status, bool $save = true): void
    {
        $worker->status = $status;
        if ($save == false) {
            $worker->save();
        }
    }

    /**
     * Включить воркера
     * @param Worker $worker
     * @return void
     */
    public function enable(Worker $worker): void
    {
        $this->changeStatus($worker, WorkerStatusEnum::available());
    }

    /**
     * Выключить воркера
     * @param Worker $worker
     * @return bool
     */
    public function disable(Worker $worker): void
    {
        $this->changeStatus($worker, WorkerStatusEnum::disabledManually());
    }

    /**
     * Список воркеров
     * @param Operator $operator
     * @return Collection|Worker[]
     */
    public function list(Operator $operator): Collection
    {
        return Worker::byOperator($operator->id)->get();
    }

    /**
     * Получить воркера по ID или кошельку
     * @param Operator $operator
     * @param string $search
     * @return Worker|null
     */
    public function getByAddressOrId(Operator $operator, string $search): ?Worker
    {
        return Worker::byOperator($operator->id)
            ->byAddressOrId($search)
            ->firstOrFail();
    }

    public function checkEnoughBalance(Worker $worker, bool $recheck = false): bool
    {
        if ($recheck) {
            BalanceReceiver::dispatchSync($worker);
            $worker->refresh();
        }

        $enough = $worker->hasEnoughBalance();
        if (!$enough) {
            $worker->status = WorkerStatusEnum::lowBalance();
            $worker->save();
        }

        if ($enough && $worker->status->equals(WorkerStatusEnum::lowBalance())) {
            $worker->status = WorkerStatusEnum::available();
            $worker->save();
        }

        return $enough;
    }
}
