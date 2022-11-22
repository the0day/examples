<?php

namespace App\Jobs;

use App\Helpers\Infura;
use App\Models\Worker;
use App\Services\WorkerService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class BalanceReceiver implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Worker
     */
    public $worker;

    /**
     * @return void
     */
    public function __construct(Worker $worker)
    {
        $this->worker = $worker;
    }

    /**
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }
        $address = $this->worker->address;
        $balance = Infura::getBalance($address);

        if (is_null($balance)) {
            $this->updateBalance();
            return;
        }

        $this->updateBalance($balance);
        Log::info("Address {$address} has {$balance} ETH.");
    }

    private function updateBalance(?float $balance = null)
    {
        $this->worker->balance = $balance;
        $this->worker->save();

        app(WorkerService::class)->hasEnoughBalance($this->worker);
    }
}
