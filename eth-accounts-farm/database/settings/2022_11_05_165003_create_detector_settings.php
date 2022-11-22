<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateDetectorSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('detector.last_block_id', 0);
        $this->migrator->add('detector.blocks_per_iteration', true);
    }
}
