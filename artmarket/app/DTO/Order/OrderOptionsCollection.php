<?php

namespace App\DTO\Order;

use Illuminate\Support\Collection;

class OrderOptionsCollection extends Collection
{
    public function __construct($items = [])
    {
        foreach ($items as $k => $item) {
            if (isset($item->price) && isset($item->price->currency) && isset($item->price->formatted)) {
                $items[$k]->price = money($item->price->amount, $item->price->currency);
            }
        }
        parent::__construct($items);
    }

    public static function fromArray(array $data): self
    {
        $collection = new self;

        foreach ($data as $id => $value) {
            $id = str_replace("'", '', $id);
            if (!is_string($value) && !is_int($value)) {
                continue;
            }

            $collection->add(new OrderOptionData(
                id: $id,
                value: $value
            ));
        }

        return $collection;
    }
}
