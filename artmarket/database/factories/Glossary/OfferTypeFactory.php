<?php

namespace Database\Factories\Glossary;

use App\Models\Glossary\OfferType;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OfferType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $title = 'offer-type-' . $this->faker->title;

        return [
            'title'       => $title,
            'description' => $this->faker->sentence(),
            'alias'       => slug($title),
            'active'      => 1
        ];
    }
}
