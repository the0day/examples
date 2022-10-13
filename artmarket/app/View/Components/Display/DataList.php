<?php

namespace App\View\Components\Display;

use Illuminate\View\Component;

class DataList extends Component
{
    public $items = [];

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function render()
    {
        return view('components.display.data-list');
    }
}
