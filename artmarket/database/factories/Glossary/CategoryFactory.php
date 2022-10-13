<?php

namespace Database\Factories\Glossary;

use App\Models\Glossary\Category;
use App\Models\Glossary\OfferType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $title = $this->faker->word;

        return [
            'title'         => $title,
            'alias'         => Str::slug($title),
            'icon'          => 'list',
            'active'        => $this->faker->boolean,
            'order'         => null,
            'parent_id'     => null,
            'offer_type_id' => function (array $attributes) {
                return $attributes['parent_id']
                    ? Category::find($attributes['parent_id'])->offer_type_id
                    : OfferType::factory();
            },
        ];
    }
}
