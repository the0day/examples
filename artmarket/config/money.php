<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel money
     |--------------------------------------------------------------------------
     */
    'locale'          => config('app.locale', 'ru_RU'),
    'defaultCurrency' => config('app.currency', 'USD'),
    //'defaultFormatter' => [DecimalMoneyFormatter::class, ['currencies' => Money::getCurrencies()]],
    'currencies'      => [
        'iso'     => 'all',
        'bitcoin' => 'all',
        'custom'  => [],
    ],
];
