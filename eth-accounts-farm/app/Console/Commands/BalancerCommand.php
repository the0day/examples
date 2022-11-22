<?php

namespace App\Console\Commands;

use App\Enums\RoleEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Operator;
use App\Models\Task;
use App\Models\Worker;
use App\Services\TaskService;
use App\Services\WorkerService;
use App\Settings\BalancerSettings;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Throwable;

class BalancerCommand extends SingleThreadCommand
{
    private TaskService $taskService;
    private BalancerSettings $balancerSettings;

    protected $signature = 'balancer';
    protected $description = 'Run balancer';

    function getCacheKey(): string
    {
        return 'balancer';
    }

    protected function process()
    {
        $this->balancerSettings = app(BalancerSettings::class);
        $this->taskService = app(TaskService::class);

        $tasks = $this->getTasks();
        $this->info("Total tasks {$tasks->count()}. Will be check " . ($this->getTasksPerIteration() == 0 ? 'all tasks' : $this->getTasksPerIteration()));
        foreach ($tasks as $task) {
            $this->assignWorker($task);
        }
    }

    /**
     * Назначит воркера
     * @throws Throwable
     */
    private function assignWorker(Task $task)
    {
        DB::transaction(function () use ($task) {
            $operator = $task->operator;
            $worker = $this->getRandomFreeWorker($operator, $task->role_id);

            if (!$worker) {
                $this->error("There is no available worker for operator #{$operator->id} and task #{$task->id}");
                return false;
            }

            $this->taskService->assignWorker($task, $worker);
            $this->warn("Worker #{$worker->id} assigned to task #{$task->id}");
        });
    }

    /**
     * Получить случайного воркера с указанной ролью
     * @param Operator $operator
     * @param RoleEnum $role
     * @return Worker|null
     *
     * @TODO: Добавить проверку роли
     */
    private function getRandomFreeWorker(Operator $operator, RoleEnum $role): ?Worker
    {
        $workerService = app(WorkerService::class);
        $workers = $workerService
            ->list($operator)
            ->shuffle();

        foreach ($workers as $worker) {
            if (!$workerService->checkEnoughBalance($worker, true)) {
                continue;
            }

            if (!$worker->canWork()) {
                continue;
            }

            return $worker;

        }

        return null;
    }

    /**
     * Получить все задачи с отсутствующими воркерами
     * @return Task[]|Collection
     */
    private function getTasks()
    {
        return Task::withoutWorker()
            ->byStatus(TaskStatusEnum::assigning())
            ->where('post_at', '>', Carbon::now()->format('Y-m-d H:i:s'))
            ->orderBy('post_at', 'ASC')
            ->orderBy('registered_at', 'ASC')
            ->lockForUpdate()
            ->get();
    }

    /**
     * Количество обрабатываемых задач за 1 запуск скрипта
     * @return int
     */
    private function getTasksPerIteration(): int
    {
        return $this->balancerSettings->tasks_per_iteration;
    }
}
