<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AccountLayout extends Component
{
    public $title;
    public $description;

    public function __construct(string $title = null, string $description = null)
    {
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return View
     */
    public function render()
    {
        return view('account.layout', [
            'title'       => $this->title,
            'description' => $this->description
        ]);
    }
}
