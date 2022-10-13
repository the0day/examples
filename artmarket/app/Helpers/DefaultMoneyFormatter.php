<?php

namespace App\Helpers;

use Cknow\Money\Money;
use Money\Formatter\DecimalMoneyFormatter;

class DefaultMoneyFormatter
{
    public function __construct()
    {
        return new DecimalMoneyFormatter(Money::getCurrencies());
    }

    public function __invoke(): array
    {
        return [];
    }
}
