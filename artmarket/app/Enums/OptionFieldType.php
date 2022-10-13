<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self text()
 * @method static self number()
 * @method static self radio()
 * @method static self select()
 * @method static self checkbox()
 */
final class OptionFieldType extends Enum
{
    protected static function values(): array
    {
        return [
            'text'     => 1,
            'number'   => 2,
            'radio'    => 3,
            'select'   => 4,
            'checkbox' => 5
        ];
    }

    protected static function labels(): array
    {
        return [
            'text'     => __('glossary_option.enum.field_type.text'),
            'number'   => __('glossary_option.enum.field_type.number'),
            'radio'    => __('glossary_option.enum.field_type.radio'),
            'select'   => __('glossary_option.enum.field_type.select'),
            'checkbox' => __('glossary_option.enum.field_type.checkbox')
        ];
    }

    public function getKey(): ?string
    {
        $values = array_flip(self::values());

        return $values[$this->value] ?? null;
    }

    public function isSelector(): bool
    {
        return $this->value == 3 || $this->value == 4;
    }

    public function isNumber(): bool
    {
        return $this->value == 2;
    }
}
