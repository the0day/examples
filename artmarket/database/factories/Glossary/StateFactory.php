<?php

namespace Database\Factories\Glossary;

use App\Models\Glossary\Country;
use App\Models\Glossary\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = State::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $country = Country::factory()->create();

        return [
            'name'         => $this->faker->word,
            'country_id'   => $country->id,
            'country_code' => $country->iso2,
            'state_code'   => $country->iso2,
        ];
    }
}
