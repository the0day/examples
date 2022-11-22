<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateBalancerSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('balancer.tasks_per_iteration', 0);
    }
}
