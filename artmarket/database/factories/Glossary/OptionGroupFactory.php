<?php

namespace Database\Factories\Glossary;

use App\Models\Glossary\OptionGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OptionGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $title = 'option-group-' . $this->faker->title;

        return [
            'title'       => $title,
            'alias'       => slug($title),
            'description' => $this->faker->sentence(),
            'type'        => $this->faker->randomNumber(1, 5),
            'active'      => 1
        ];
    }
}
