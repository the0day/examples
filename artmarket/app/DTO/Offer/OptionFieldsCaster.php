<?php

namespace App\DTO\Offer;

use Spatie\DataTransferObject\Caster;

class OptionFieldsCaster implements Caster
{
    public function cast(mixed $fields): array
    {
        return array_map(
            fn(array $data) => new OptionsFieldsData(...$data),
            $fields
        );
    }
}
