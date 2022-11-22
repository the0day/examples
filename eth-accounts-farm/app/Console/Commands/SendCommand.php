<?php

namespace App\Console\Commands;

use App\Enums\TaskStatusEnum;
use App\Helpers\Infura;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Web3\Utils;
use Web3p\EthereumTx\Transaction;

class SendCommand extends SingleThreadCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run sender';


    protected function process()
    {
        $tasks = $this->getTasks();
        $this->info("Total tasks {$tasks->count()} with workers, but transactions was not executed.");

        foreach ($tasks as $task) {
            $this->signAndSend($task);
        }
    }

    protected function getCacheKey(): string
    {
        return 'sender';
    }

    private function getTasks()
    {
        return Task::withoutWorker()
            ->byStatus(TaskStatusEnum::assigned())
            ->lockForUpdate()
            ->get();
    }

    /**
     * @param Task $task
     * @return string|null
     */
    private function signAndSend(Task $task): ?string
    {
        $nonce = hexdec((string)Infura::getTransactionCount($task->worker->address));
        $data = $task->getTransactionData();
        $data['nonce'] = Utils::toHex($nonce + 1, true);

        $transaction = new Transaction($data);
        $data['gas'] = Utils::toHex(2000000, true);
        $data['gasPrice'] = (string)Infura::post('eth_estimateGas', [$transaction]);

        $transaction = new Transaction($data);
        $signed = $transaction->sign($task->worker->private_key);

        $response = Infura::post('eth_sendRawTransaction', [$signed]);
        if ($response->hasError()) {
            $task->response = [
                'code'    => $response->getErrorCode(),
                'message' => $response->getErrorMessage()
            ];
            $task->save();
            return null;
        }

        $task->tx_temp_hash = (string)$response;
        $task->status = TaskStatusEnum::receipt();
        $task->executed_at = Carbon::now();
        $task->save();

        return $response;
    }
}
