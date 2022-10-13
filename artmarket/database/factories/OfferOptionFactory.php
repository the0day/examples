<?php

namespace Database\Factories;

use App\Models\OfferOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OfferOption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'option_id'    => 0,
            'offer_id'     => 0,
            'name'         => '',
            'field_values' => [],
            'days'         => null,
            'price'        => null,
            'currency'     => 'USD'
        ];
    }
}
