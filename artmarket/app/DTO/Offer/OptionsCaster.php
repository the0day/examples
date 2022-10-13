<?php

namespace App\DTO\Offer;

use Exception;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class OptionsCaster implements Caster
{
    /**
     * @throws UnknownProperties
     */
    public function cast(mixed $value): array
    {
        if (!is_array($value)) {
            throw new Exception("Can only cast arrays to OptionsData");
        }

        $return = [];
        foreach ($value as $key => $option) {
            if (!isset($option['name'])) {
                continue;
            }
            $return[$key] = new OptionsData(...$option);
        }

        return $return;
    }
}
