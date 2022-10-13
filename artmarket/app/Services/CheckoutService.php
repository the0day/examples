<?php

namespace App\Services;

use App\DTO\Order\OrderOptionsCollection;
use App\Models\Offer;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatus\Payment;
use Cknow\Money\Money;
use Illuminate\Contracts\Container\BindingResolutionException;

class CheckoutService
{
    /**
     * Создать заказ
     * @param User $user
     * @param Offer $offer
     * @param OrderOptionsCollection|null $upgrades
     * @param bool $save
     * @return Order
     * @throws BindingResolutionException
     */
    public function create(User $user, Offer $offer, OrderOptionsCollection $upgrades = null, bool $save = true): Order
    {
        $order = app()->make(OrderService::class)->getOrCreate($user, $offer);
        $order->job_cost = clone $offer->price;
        $order->currency = $offer->currency;

        if ($upgrades) {
            $order->addUpgrades($upgrades);
        }

        $this->calculateTotal($order);

        if ($save) {
            $order->save();
            $order->buyer->notify(new Payment($order));
        }

        return $order;
    }

    /**
     * Установить стоимости и таксы
     * @param Order $order
     * @return void
     */
    public function calculateTotal(Order $order): void
    {
        $order->discount = Money::USD(0);
        $order->service_fee = Money::USD(0);
        $order->total_cost = Money::sum($order->job_cost, $order->getUpgradesPrice());
        $order->admin_fee = $this->calculateAdminFee($order->total_cost);
    }

    /**
     * Рассчитать таксы проекта
     * @param Money $price
     * @return Money
     */
    public static function calculateAdminFee(Money $price): Money
    {
        return money(settings('fees') / 100 * $price->getAmount(), $price->getCurrency());
    }
}
