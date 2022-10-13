<?php

namespace App\DTO\Order;

use App\DTO\Casters\MoneyCaster;
use App\DTO\ObjectData;
use Cknow\Money\Money;
use Spatie\DataTransferObject\Attributes\CastWith;

class OrderOptionData extends ObjectData
{
    public int $id;
    public string|int $value;
    #[CastWith(MoneyCaster::class)]
    public ?Money $price;
}
