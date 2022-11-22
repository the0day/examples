<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.infura_key', '');
        $this->migrator->add('general.min_balance', 0);
    }
}
