<?php

namespace App\Jobs;

use App\Enums\TaskStatusEnum;
use App\Helpers\Infura;
use App\Models\Task;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class ReceiptReceiver implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Task
     */
    public $task;

    /**
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        if ($this->task->hasTxHash()) {
            Log::info("Task #{$this->task->id}: already has txHash");
            return;
        }


        $response = Infura::post('eth_getTransactionReceipt', [$this->task->tx_temp_hash]);
        if ($response->hasError()) {
            Log::info("Task #{$this->task->id}: can not receive a receipt", [$response->getErrorCode(), $response->getErrorMessage()]);
            return;
        }

        if (isset($response->getResponse()['result']) && $response->getResponse()['result'] !== null) {
            $this->task->tx_hash = $response->getResponse()['result'];
            $this->task->status = TaskStatusEnum::accepted();
            $this->task->save();
        }
    }
}
