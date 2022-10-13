<?php

namespace Database\Factories\Glossary;

use App\Models\Glossary\Option;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Option::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $title = 'option-' . $this->faker->title;

        return [
            'title'       => $title,
            'description' => $this->faker->sentence(),
            'alias'       => slug($title),
            'price'       => $this->faker->randomNumber(2),
            'currency'    => 'USD',
            'field_type'  => mt_rand(1, 5),
            'active'      => 1
        ];
    }
}
