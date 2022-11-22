<?php

namespace App\Http\Controllers\API;


use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\WorkerControlRequest;
use App\Http\Requests\API\WorkerListRequest;
use App\Http\Requests\API\WorkerRegisterRequest;
use App\Http\Resources\WorkerCollection;
use App\Http\Resources\WorkerResource;
use App\Jobs\BalanceReceiver;
use App\Models\Worker;
use App\Services\OperatorService;
use App\Services\WorkerService;
use Bus;
use Throwable;

class WorkerController extends Controller
{
    private WorkerService $workerService;
    private OperatorService $operatorService;

    public function __construct(WorkerService $workerService, OperatorService $operatorService)
    {
        $this->workerService = $workerService;
        $this->operatorService = $operatorService;
    }

    /**
     * Зарегистрировать оператора и воркеров
     * @param WorkerRegisterRequest $request
     * @return Worker[]
     */
    public function register(WorkerRegisterRequest $request)
    {
        $operator = $this->operatorService->create(
            $request->get('chain_id'),
            $request->get('label'),
            $request->get('task_callback'),
            $request->get('status_callback'),
        );

        $roleEnum = RoleEnum::from($request->get('role'));

        return $this->workerService->createBunch($operator, $roleEnum, $request->get('q'), $request->get('pk'));
    }

    /**
     * Список воркеров
     * @param WorkerListRequest $request
     * @return WorkerCollection
     * @throws Throwable
     */
    public function list(WorkerListRequest $request)
    {
        $operator = $this->operatorService->getByKey($request->get('key'));

        $workers = $this->workerService->list($operator);
        if ($request->get('show_balances')) {
            $getBalances = $workers->map(function ($v) {
                return new BalanceReceiver($v);
            });

            $batch = Bus::batch($getBalances)
                ->allowFailures()
                ->dispatch();

            while (($batch = $batch->fresh()) && !$batch->finished()) {
                sleep(1);
            }
        }

        $workers = $this->workerService->list($operator);

        return new WorkerCollection($workers);
    }

    /**
     * Выключить воркера
     * @param WorkerControlRequest $request
     * @return WorkerResource
     */
    public function disable(WorkerControlRequest $request)
    {
        $worker = $this->getWorkerByOperator($request->get('key'), $request->get('worker'));

        $this->workerService->disable($worker);

        return new WorkerResource($worker);
    }

    /**
     * Включить воркера
     * @param WorkerControlRequest $request
     * @return WorkerResource
     */
    public function enable(WorkerControlRequest $request)
    {
        $worker = $this->getWorkerByOperator($request->get('key'), $request->get('worker'));

        $this->workerService->enable($worker);

        return new WorkerResource($worker);
    }

    /**
     * Получить воркера по ключу оператора
     * @param string $operatorKey
     * @param string $worker
     * @return Worker|null
     */
    private function getWorkerByOperator(string $operatorKey, string $worker): ?Worker
    {
        $operator = $this->operatorService->getByKey($operatorKey);
        return $this->workerService->getByAddressOrId($operator, $worker);
    }
}
