<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class BalancerSettings extends Settings
{
    public int $tasks_per_iteration;

    public static function group(): string
    {
        return 'balancer';
    }
}
