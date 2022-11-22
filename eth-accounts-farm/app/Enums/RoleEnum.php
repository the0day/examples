<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self none()
 * @method static self admin()
 */
final class RoleEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'none'  => 0,
            'admin' => 1,
        ];
    }
}
