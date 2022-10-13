<?php

namespace Tests\Unit;

use App\DTO\Order\OrderOptionsCollection;
use App\Enums\OrderStatus;
use App\Models\Offer;
use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\OrderService;
use App\Services\UserService;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Tests\Traits\OfferTrait;
use Tests\Traits\OrderTrait;

class CheckoutServiceTest extends TestCase
{
    use OfferTrait, OrderTrait;

    public function testUserCreditCanIncrease()
    {
        $customer = $this->createUser();
        $this->userService->addCredit($customer, $added = Money::USD(1000));

        $this->assertEquals($added->getAmount(), $customer->credit->getAmount());
    }

    public function testInvalidPaymentWithZeroBalance()
    {
        $this->expectExceptionMessage('no enough funds');

        $order = Order::factory()->create([
            'total_cost' => Money::USD(1000)
        ]);
        $this->orderService->doPayment($order);
    }

    public function testItCanCreateAnOrder()
    {
        $offer = Offer::factory(['price' => Money::USD(1000)])->create();
        $customer = $this->createUser();

        // Create an order
        // check: orders count
        $created = $this->checkoutService->create($customer, $offer);
        $orders = $this->getOrders($customer->id, $offer->id);
        $this->assertEquals(1, $orders->count());

        // check created order: price, currency
        $order = $orders->first();
        $this->assertEquals($created->id, $order->id);
        $this->assertEquals(OrderStatus::payment(), $order->status);
        $this->assertEquals($offer->price, $order->job_cost);
        $this->assertEquals($offer->currency, $order->currency);

        $this->doPayment($order);
        $this->assertEquals($order->status, OrderStatus::accepting());
        $this->assertEquals($order->total_cost, $offer->price);

        $admin_fee = Money::USD($offer->price->getAmount() * (settings('fees') / 100));
        $this->assertEquals($order->admin_fee, $admin_fee);
    }

    private function getOrders(int $customerId, int $offerId): Collection
    {
        return Order::where('user_id', '=', $customerId)
            ->where('offer_id', '=', $offerId)
            ->get();
    }

    private function doPayment(Order $order)
    {
        $offer = $order->offer;
        $customer = $order->buyer;
        $this->userService->addCredit($customer, $offer->price);
        $oldCredit = clone $customer->credit;

        // Increase credit for the customer
        // check: order status, order price
        $this->orderService->doPayment($order);

        $this->assertEquals($oldCredit->subtract($order->total_cost), $customer->credit);
    }

    public function testItCanCreateAnOrderWithUpdates()
    {
        $offer = $this->createOfferWithOptions();
        $customer = $this->createUser();
        $options = $this->getOptionsData($offer);

        $created = $this->checkoutService->create($customer, $offer, OrderOptionsCollection::fromArray($options));
        $this->assertDatabaseHas('orders', ['id' => $created->id]);
        $this->checkOrderPrices($created, $offer, $options);
    }


    private function checkOrderPrices(Order $order, Offer $offer, array $updates)
    {
        $offerOptions = $offer->options;
        $upgradeTotal = money(0, $order->currency);
        foreach ($offerOptions as $offerOption) {
            $this->checkOrderOption($offerOption, $order->upgrades, $updates[$offerOption->id]);

            $fieldType = $offerOption->field_type;

            $upgradePrice = $fieldType->isSelector()
                ? $offerOption->getPrice($updates[$offerOption->id])
                : $offerOption->getPrice();

            if ($upgradePrice) {
                $upgradeTotal = $upgradeTotal->add($upgradePrice);
            }

        }

        $order->getUpgradesPrice();
        $this->assertEquals($order->getUpgradesPrice(), $upgradeTotal, 'invalid upgrade total costs');
        $this->assertEquals($order->getUpgradesPrice(), $order->upgrade_cost, 'invalid order upgrade_cost attribute');
        $this->assertEquals($order->admin_fee, CheckoutService::calculateAdminFee($order->total_cost));
    }

    private function checkOrderOption($offerOption, OrderOptionsCollection $upgrades, $valueSent)
    {
        $upgrade = $upgrades->firstWhere('id', $offerOption->id);
        if (!$offerOption->isOrderUpgrade()) {
            return;
        }

        if ($offerOption->field_type->isSelector()) {
            $this->assertEquals($upgrade->price->getAmount(), $offerOption->getPrice($valueSent)->getAmount());
        } else {
            $price = $offerOption->getPrice();
            if ($offerOption->field_type->isNumber()) {
                $price->multiply($valueSent);
            }
            $this->assertEquals($upgrade->price->getAmount(), $price->getAmount());
        }
    }

    public function testMakeDoubleOrder()
    {
        $offer = Offer::factory(['price' => Money::USD(1000)])->create();
        $customer = $this->createUser();
        $firstOrder = $this->checkoutService->create($customer, $offer);
        $secondOrder = $this->checkoutService->create($customer, $offer);
        $this->assertEquals($firstOrder->id, $secondOrder->id, 'Orders are not equal');
    }

    public function testCreateAndEditAnOrder()
    {
        $offer = $this->createOfferWithOptions();
        $customer = $this->createUser();

        $options = $this->getOptionsData($offer);
        $this->checkoutService->create($customer, $offer, OrderOptionsCollection::fromArray($options));
        $orderCreated = $this->orderService->getFirstOrder($customer, $offer);

        $this->checkoutService->create($customer, $offer, OrderOptionsCollection::fromArray($options));
        $orderUpdated = $this->orderService->getFirstOrder($customer, $offer);

        self::assertEquals($orderCreated, $orderUpdated);
    }

    public function testCreateSecondOrderAfterPayment()
    {
        $firstOrder = $this->createOrderAndMarkPaid();
        $secondOrder = $this->createOrder(null, $firstOrder->buyer);

        $this->assertEquals(OrderStatus::accepting(), $firstOrder->status, 'first order has wrong status');
        $this->assertEquals(OrderStatus::payment(), $secondOrder->status, 'second order has wrong status');
        $this->assertNotEquals($firstOrder->id, $secondOrder->id, 'The IDs are the same');
        $this->assertCount(2, $firstOrder->buyer->orders, 'There are not 2 orders for an user');
    }

    protected function setUp(): void
    {
        $this->orderService = new OrderService();
        $this->userService = new UserService();
        parent::setUp(); // TODO: Change the autogenerated stub
    }

}
