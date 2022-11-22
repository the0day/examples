<?php

namespace App\Services;

use App\Models\Operator;
use Hash;

class OperatorService
{
    public function create(int $chainId, string $label, string $taskCallback, string $statusCallback): Operator
    {
        $operator = new Operator();
        $operator->chain_id = $chainId;
        $operator->label = $label;
        $operator->key = $this->generateHash();
        $operator->task_callback = $taskCallback;
        $operator->status_callback = $statusCallback;
        $operator->save();

        return $operator;
    }

    public function getByKey(string $key): ?Operator
    {
        return Operator::where('key', $key)->firstOrFail();
    }

    public function generateHash(): string
    {
        return Hash::make(microtime() . 'f@rmZ3r0');
    }
}
