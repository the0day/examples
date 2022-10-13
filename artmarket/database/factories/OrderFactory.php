<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Offer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'      => User::factory(),
            'offer_id'     => Offer::factory(),
            'seller_id'    => function (array $attributes) {
                return Offer::find($attributes['offer_id'])->user_id;
            },
            'status'       => OrderStatus::payment(),
            'job_cost'     => function (array $attributes) {
                return Offer::find($attributes['offer_id'])->price;
            },
            'upgrades'     => null,
            'discount'     => 0,
            'admin_fee'    => 0,
            'service_fee'  => 0,
            'upgrade_cost' => 0,
            'total_cost'   => function (array $attributes) {
                return Offer::find($attributes['offer_id'])->price;
            },
            'currency'     => 'USD'
        ];
    }
}
