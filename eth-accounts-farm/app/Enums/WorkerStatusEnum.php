<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self available()
 * @method static self busy()
 * @method static self disabledManually()
 * @method static self lowBalance()
 */
final class WorkerStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'available'        => 0,
            'busy'             => 3,
            'disabledManually' => 1,
            'lowBalance'       => 2,
        ];
    }
}
