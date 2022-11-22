<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DetectorSettings extends Settings
{
    public int $last_block_id;
    public int $blocks_per_iteration;

    public static function group(): string
    {
        return 'detector';
    }
}
