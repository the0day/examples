<?php

namespace GameData;

interface WarmupableGameData
{
    public function import(array $data): void;

    public function warmUp(): array;

    public function update(): void;
}