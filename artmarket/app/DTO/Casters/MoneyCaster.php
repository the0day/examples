<?php

namespace App\DTO\Casters;

use Cknow\Money\Money;
use Spatie\DataTransferObject\Caster;

class MoneyCaster implements Caster
{
    /**
     * @param array|mixed $value
     * @return mixed
     */
    public function cast(mixed $value): Money
    {
        return $value;
    }
}
