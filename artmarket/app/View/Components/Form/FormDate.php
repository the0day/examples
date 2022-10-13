<?php

namespace App\View\Components\Form;

use ProtoneMedia\LaravelFormComponents\Components\Component;
use ProtoneMedia\LaravelFormComponents\Components\HandlesDefaultAndOldValue;
use ProtoneMedia\LaravelFormComponents\Components\HandlesValidationErrors;

class FormDate extends Component
{
    use HandlesValidationErrors;
    use HandlesDefaultAndOldValue;

    public string $name;
    public string $label;
    public bool $floating;

    public $value;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $name,
        string $label = '',
        string $type = 'text',
               $bind = null,
               $default = null,
               $language = null,
        bool   $showErrors = true,
        bool   $floating = false
    )
    {
        $this->name = $name;
        $this->label = $label;
        $this->showErrors = $showErrors;
        $this->floating = $floating && $type !== 'hidden';

        if ($language) {
            $this->name = "{$name}[{$language}]";
        }

        $this->setValue($name, $bind, $default, $language);
    }
}
