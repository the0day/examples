<?php

namespace App\View\LivewireTable;


class Money extends \Mediconesystems\LivewireDatatables\Column
{
    public $type = 'string';
    public $callback;


    public function __construct()
    {
        $this->callback = function ($value, $row) {
            return money($value, $row->currency ?? 'USD');
        };

        return $this;
    }
}
