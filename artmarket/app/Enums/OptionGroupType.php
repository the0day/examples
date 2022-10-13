<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self extra()
 * @method static self info()
 */
final class OptionGroupType extends Enum
{
    protected static function values(): array
    {
        return [
            'extra' => 1,
            'info'  => 2
        ];
    }

    protected static function labels(): array
    {
        return [
            'extra' => __('glossary_option_group.enum.type.extra'),
            'info'  => __('glossary_option_group.enum.type.info'),
        ];
    }

    public function isInfo(): bool
    {
        return $this->equals(self::info());
    }
}
