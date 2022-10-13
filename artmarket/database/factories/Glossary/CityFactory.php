<?php

namespace Database\Factories\Glossary;

use App\Models\Glossary\City;
use App\Models\Glossary\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = City::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $state = State::factory()->create();

        return [
            'name'         => $this->faker->city,
            'state_id'     => $state->id,
            'state_code'   => $this->faker->countryISOAlpha3,
            'country_id'   => $state->country_id,
            'country_code' => $state->country_code,
            'latitude'     => $this->faker->latitude,
            'longitude'    => $this->faker->longitude,
        ];
    }
}
