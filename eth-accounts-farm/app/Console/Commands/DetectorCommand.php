<?php

namespace App\Console\Commands;

use App\Helpers\Infura;
use App\Settings\DetectorSettings;
use DB;

class DetectorCommand extends SingleThreadCommand
{

    private DetectorSettings $detectorSettings;
    private array $txIds = [];

    protected $signature = 'detector';
    protected $description = 'Run detector';

    protected function getCacheKey(): string
    {
        return 'detector';
    }

    protected function process()
    {

        $this->detectorSettings = app(DetectorSettings::class);

        $i = 0;
        $this->info("{$this->getBlocksPerIteration()} blocks are going to check.");
        $this->info("The last checked block ID: {$this->getCheckedLastBlockId()}.");
        while (true) {
            if ($this->getBlocksPerIteration() <= $i or !$this->tick()) {
                break;
            }
            $i++;
        }
    }

    private function tick(): bool
    {
        return DB::transaction(function () {
            $blockHash = Infura::getCurrentBlockId();
            $blockId = hexdec($blockHash);
            if ($this->isCurrentBlock($blockId)) {
                $this->error("The block #{$blockId} already checked.");
                return false;
            }

            $checkedLastBlockId = $this->getCheckedLastBlockId();
            if ($blockId < $this->getCheckedLastBlockId()) {
                $this->error("The block #{$blockId} below than {$checkedLastBlockId}");
                return false;
            }

            $nextBlockId = $checkedLastBlockId + 1;
            $this->warn("Checking the block #{$nextBlockId}.");

            /*
            $tasks = Task::byStatus(TaskStatusEnum::accepted())->get();
            $txIds = $tasks->pluck('tx_hash')->toArray();
            $transactions = Infura::retrieveTransactions('0x'.dechex($nextBlockId), $txIds);
            dd($transactions);
            */

            $this->updateLastBlockId($nextBlockId);
            return true;
        });
    }

    private function updateLastBlockId(int $lastBlockId)
    {
        $this->detectorSettings->last_block_id = $lastBlockId;
        $this->detectorSettings->save();
    }

    private function isCurrentBlock(int $lastBlockId): bool
    {
        return $this->getCheckedLastBlockId() == $lastBlockId;
    }

    private function getCheckedLastBlockId(): int
    {
        return $this->detectorSettings->last_block_id;
    }

    private function getBlocksPerIteration(): int
    {
        return $this->detectorSettings->blocks_per_iteration;
    }
}
