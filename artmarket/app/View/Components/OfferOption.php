<?php

namespace App\View\Components;

use App\Enums\OptionFieldType;
use App\Models\Glossary\Option;
use Illuminate\View\Component;

class OfferOption extends Component
{

    public $option = null;
    public $value = null;

    public function __construct(Option $option, mixed $value = null)
    {
        $this->option = $option;
        $this->value = $value;
    }

    public function render()
    {
        $option = $this->option;

        $attributes = [
            'id'    => $option->alias,
            'value' => $option->value ?? ''
        ];

        switch ($option->field_type) {
            case OptionFieldType::text():
                $layout = 'text';
                break;

            case OptionFieldType::checkbox():
                $layout = 'checkbox';
                break;

            case OptionFieldType::number():
                $layout = 'text';
                $attributes += ['type' => 'number'];
                break;

            case OptionFieldType::radio():
                $layout = 'radio';
                break;

            case OptionFieldType::select():
                $layout = 'select';
                $attributes += ['items' => json_decode($option->field_values)];
                break;
        }

        if (!isset($layout)) {
            return null;
        }

        return view('components.form.' . $layout, $attributes);
    }
}
