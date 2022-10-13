<?php

namespace App\View\LivewireTable;


class Translatable extends \Mediconesystems\LivewireDatatables\Column
{
    public $type = 'json';
    public $callback;

    public function __construct()
    {

        $this->callback = function ($value) {
            $locale = app()->getLocale();
            $translates = json_decode($value);
            return $translates->{$locale} ?? '';
        };

        return $this;
    }
}
