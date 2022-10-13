<?php

namespace App\Http\Livewire;

use App\Models\Glossary\City;
use Illuminate\Support\Collection;

class CitySelect extends LiveSelect
{
    public function options($searchTerm = null): Collection
    {
        return City::query()
            ->when($this->hasDependency('country_id'), function ($query) {
                $query->where('country_id', $this->getDependingValue('country_id'));
            })->
            when($searchTerm, function ($query, $searchTerm) {
                $query->where('name', 'like', "%$searchTerm%");
            })
            ->limit(20)
            ->get()
            ->map(function (City $city) {
                return [
                    'value'       => $city->id,
                    'description' => $city->name,
                ];
            });
    }

    public function selectedOption($value)
    {
        $city = City::find($value);

        return [
            'title'       => optional($city)->name,
            'description' => optional($city)->name,
        ];
    }
}
