<?php

namespace App\DTO\Casters;

use App\DTO\Order\OrderOptionsCollection;
use Spatie\DataTransferObject\Caster;

class OrderOptionsCaster implements Caster
{

    public function cast(mixed $value): OrderOptionsCollection
    {
        $items = [];
        foreach ($value as $data) {
            $items[] = new OrderOptionsCollection(...$data);
        }

        return new OrderOptionsCollection($items);
    }
}
