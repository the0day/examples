<?php

namespace Database\Factories\Glossary;

use App\Models\Glossary\OfferPurpose;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurposeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OfferPurpose::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $title = 'purpose-' . $this->faker->title;

        return [
            'offer_type_id' => function () {
                return OfferTypeFactory::factory();
            },
            'title'         => $title,
            'alias'         => slug($title),
            'active'        => 1
        ];
    }
}
