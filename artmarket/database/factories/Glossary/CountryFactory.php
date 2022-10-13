<?php

namespace Database\Factories\Glossary;

use App\Models\Glossary\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Country::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name'       => $this->faker->country,
            'iso3'       => $this->faker->countryISOAlpha3,
            'iso2'       => $this->faker->countryCode,
            'phone_code' => $this->faker->numberBetween(1, 999),
            'capital'    => $this->faker->city,
            'currency'   => $this->faker->currencyCode,
        ];
    }
}
