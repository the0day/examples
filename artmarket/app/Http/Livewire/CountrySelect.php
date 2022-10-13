<?php

namespace App\Http\Livewire;

use App\Models\Glossary\Country;
use Illuminate\Support\Collection;

class CountrySelect extends LiveSelect
{
    public function options($searchTerm = null): Collection
    {
        return Country::when($searchTerm, function ($query, $searchTerm) {
            $query->where('name', 'like', "%$searchTerm%");
        })
            ->get()
            ->map(function (Country $country) {
                return [
                    'value'       => $country->id,
                    'description' => $country->name,
                ];
            });
    }

    public function selectedOption($value)
    {
        $country = Country::find($value);

        return [
            'title'       => optional($country)->name,
            'description' => optional($country)->name,
        ];
    }
}
