<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Offer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => Offer::factory(),
            'user_id'  => function (array $attributes) {
                $order = Order::find($attributes['order_id']);
                return mt_rand(0, 100) >= 50 ? $order->user_id : $order->seller_id;
            },
            'body'     => $this->faker->text
        ];
    }
}
