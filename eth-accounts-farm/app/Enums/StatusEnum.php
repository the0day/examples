<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self draft()
 * @method static self published()
 * @method static self archived()
 */
final class StatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'draft'     => 1,
            'published' => 2,
            'archived'  => 3,
        ];
    }
}
