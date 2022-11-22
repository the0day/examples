<?php

namespace App\Console\Commands;

use App\Enums\TaskStatusEnum;
use App\Jobs\ReceiptReceiver;
use App\Models\Task;
use Illuminate\Console\Command;

class ReceiptReceiverCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run receipt receiver';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = $this->getTasks();
        $this->info("Receipt receiver is running");
        $this->info($tasks->count() . " task(s) with receipt status");
        foreach ($tasks as $task) {
            ReceiptReceiver::dispatchSync($task);
            $task->refresh();
            if ($task->hasTxHash()) {
                $this->info("Task {$task->id} received txHash: {$task->tx_hash}");
            } else {
                $this->info("Task {$task->id} did not receive receipt: " . json_encode($task->response));
            }
        }

        return Command::SUCCESS;
    }

    private function getTasks()
    {
        return Task::withoutWorker()
            ->byStatus(TaskStatusEnum::receipt())
            ->orderBy('executed_at', 'ASC')
            ->get();
    }
}
