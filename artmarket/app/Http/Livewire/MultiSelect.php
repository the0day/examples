<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MultiSelect extends Component
{
    public $items;

    public function render()
    {
        return view('livewire.multi-select');
    }
}
