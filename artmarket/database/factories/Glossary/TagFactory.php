<?php

namespace Database\Factories\Glossary;

use App\Models\Glossary\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'tag'        => $this->faker->word,
            'used_times' => 0,
            'active'     => 1
        ];
    }
}
