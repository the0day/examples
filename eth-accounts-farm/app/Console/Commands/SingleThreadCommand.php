<?php

namespace App\Console\Commands;

use Cache;
use Illuminate\Console\Command;

abstract class SingleThreadCommand extends Command
{
    abstract protected function process();

    abstract protected function getCacheKey(): string;

    public function handle()
    {
        $key = $this->getCacheKey();
        $running = Cache::get($key);
        if ($running > time()) {
            $this->error("Another {$key} thread is already running. Left " . ($running - time()) . " seconds");
            return Command::FAILURE;
        }
        Cache::set($key, time() + 59);

        $runTime = time();
        $this->info(ucfirst($key) . " is running.");

        $this->process();
        $executionTime = time() - $runTime;
        $this->info(ucfirst($key) . " finished (execution time: {$executionTime} secs).");
        Cache::forget($key);

        return Command::SUCCESS;
    }
}
