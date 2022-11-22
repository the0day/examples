<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateTaskRequest;
use App\Services\OperatorService;
use App\Services\TaskService;
use App\Services\WorkerService;

class TaskController extends Controller
{
    private TaskService $taskService;
    private OperatorService $operatorService;
    private WorkerService $workerService;

    public function __construct(TaskService $taskService, OperatorService $operatorService, WorkerService $workerService)
    {
        $this->taskService = $taskService;
        $this->operatorService = $operatorService;
        $this->workerService = $workerService;
    }

    public function create(CreateTaskRequest $request)
    {
        $operator = $this->operatorService->getByKey($request->get('key'));

        $tasks = $this->taskService->createBunch($operator, $request->get('tasks'));

        //return
    }
}
