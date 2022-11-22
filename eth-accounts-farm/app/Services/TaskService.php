<?php

namespace App\Services;

use App\Dto\TaskDto;
use App\Enums\TaskStatusEnum;
use App\Enums\WorkerStatusEnum;
use App\Models\Operator;
use App\Models\Task;
use App\Models\Worker;
use Carbon\Carbon;
use Str;

class TaskService
{
    /**
     * @param Operator $operator
     * @param TaskDto $taskDto
     * @return Task
     */
    public function create(Operator $operator, TaskDto $taskDto): Task
    {
        $task = new Task();
        $task->operator_id = $operator->id;
        $task->tx_to = $taskDto->txTo;
        $task->tx_value = $taskDto->txValue;
        $task->tx_data = $taskDto->txData;
        $task->status = TaskStatusEnum::assigning();
        $task->post_at = $taskDto->postAt ?? null;
        $task->post_at_node = $taskDto->postAtNote ?? null;
        $task->role_id = $taskDto->role_id;
        $task->priority = $taskDto->priority;
        $task->uuid = Str::uuid()->toString();
        $task->registered_at = Carbon::now();
        $task->save();

        return $task;
    }

    /**
     * @param Operator $operator
     * @param array $tasks
     * @return Task[]
     */
    public function createBunch(Operator $operator, array $tasks): array
    {
        $list = [];

        foreach ($tasks as $task) {
            $taskDto = new TaskDto(
                txTo: $task['tx_to'],
                txValue: $task['tx_value'],
                txData: $task['tx_data'],
                priority: $task['priority'],
                postAt: $task['post_at'],
                postAtNote: $task['post_at_node'],
                role_id: $task['role_id']
            );

            $list[] = $this->create($operator, $taskDto);
        }

        return $list;
    }

    public function assignWorker(Task $task, Worker $worker): void
    {
        $task->worker_id = $worker->id;
        $task->status = TaskStatusEnum::assigned();
        $task->save();

        $worker->status = WorkerStatusEnum::busy();
        $worker->save();
    }
}
