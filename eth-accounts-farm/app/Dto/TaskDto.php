<?php

namespace App\Dto;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class TaskDto extends Data
{
    public function __construct(
        public string  $txTo,
        public string  $txValue,
        public string  $txData,
        public ?int    $priority,
        public ?string $postAt,
        public ?string $postAtNote,
        public int     $role_id = 0
    )
    {
    }
}
