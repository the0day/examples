<?php

namespace App\DTO\Offer;

use App\DTO\ObjectData;
use Spatie\DataTransferObject\Attributes\CastWith;

class OptionsData extends ObjectData
{
    public string $name;

    /** @var OptionsFieldsData[] */
    #[CastWith(OptionFieldsCaster::class)]
    public ?array $fields;

    public function getModelData()
    {
        if (!$this->name) {
            return null;
        }

        if (is_array($this->fields) && !$this->hasSingleField()) {
            foreach ($this->fields as $fields) {
                $fields->price *= 100;
            }
        }

        return [
            'name'         => $this->name,
            'days'         => $this->hasSingleField() ? $this->fields[0]->days : null,
            'price'        => $this->hasSingleField() ? $this->fields[0]->price : null,
            'field_values' => !$this->hasSingleField() ? $this->fields : null
        ];
    }

    public function hasSingleField(): bool
    {
        return isset($this->fields[0]) && count($this->fields) == 1;
    }
}
