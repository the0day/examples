<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public ?string $infura_key;
    public ?float $min_balance;

    public static function group(): string
    {
        return 'general';
    }
}
