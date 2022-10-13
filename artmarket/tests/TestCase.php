<?php

namespace Tests;

use App;
use App\DTO\Order\OrderOptionsCollection;
use App\Enums\OrderStatus;
use App\Models\Message;
use App\Models\Offer;
use App\Models\Order;
use App\Models\User;
use App\Services\ChatService;
use App\Services\CheckoutService;
use App\Services\OrderService;
use App\Services\OrderStatusService;
use App\Services\UserService;
use DB;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\TestResponse;
use Tests\Traits\OfferTrait;
use Tests\Traits\OrderTrait;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;
    use OfferTrait, OrderTrait;

    protected OrderService $orderService;
    protected UserService $userService;
    protected ChatService $chatService;
    protected OrderStatusService $orderStatusService;
    protected CheckoutService $checkoutService;

    protected function setUp(): void
    {
        parent::setUp();
        if (DB::getConfig()['name'] == 'mysql') {
            die('wrong env');
        }
        App::setLocale('ru');
        Artisan::call('db:seed --class=SettingsSeeder');
        Artisan::call('db:seed --class=GlossarySeeder');
        Artisan::call('db:seed --class=PaymentMethodsSeeder');

        $this->orderService = new OrderService();
        $this->userService = new UserService();
        $this->chatService = new ChatService();
        $this->orderStatusService = new OrderStatusService();
        $this->checkoutService = new CheckoutService();
    }

    public function defaultPaymentGateway(): string
    {
        return 'wallet';
    }

    protected function createUser(): User
    {
        return User::factory()->create();
    }

    protected function createOrder(?Offer $offer = null, ?User $customer = null): Order
    {
        $customer = $customer ?? $this->createUser();
        $offer = $offer ?? $this->createOfferWithOptions();
        $options = $this->getOptionsData($offer);

        return $this->checkoutService->create($customer, $offer, OrderOptionsCollection::fromArray($options));
    }

    protected function createOrderDirectly(array $orderAttributes = [], ?Offer $offer = null, ?User $customer = null): Order
    {
        $customer = $customer ?? $this->createUser();
        $offer = $offer ?? $this->createOfferWithOptions();
        $price = $offer->price->getAmount();

        return Order::factory()->create(array_merge([
            'user_id'    => $customer->id,
            'offer_id'   => $offer->id,
            'seller_id'  => $offer->user_id,
            'status'     => OrderStatus::payment(),
            'job_cost'   => $price,
            'total_cost' => $price,
            'currency'   => $offer->currency,
        ], $orderAttributes));
    }

    /**
     * Create an user, offer and order
     * @return Order
     * @throws BindingResolutionException
     */
    public function prepareOrder(): Order
    {
        $customer = $this->createUser();
        $offer = $this->createOfferWithOptions();
        $options = $this->getOptionsData($offer);

        return $this->checkoutService->create($customer, $offer, OrderOptionsCollection::fromArray($options));
    }

    public function createMessagesAtOrder(Order $order, int $count = 1)
    {
        return Message::factory(['order_id' => $order->id])->count($count)->create();
    }

    public function sendMessage(Order $order, User $user, string $message = 'test message', array $images = []): TestResponse
    {
        return $this->actingAs($user)
            ->post(route('account.orders.sendMessage', $order->id), ['message' => $message, 'image' => $images])
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['user_id', 'body', 'order_id', 'id']]);
    }

    /**
     * @return Order
     */
    public function getRandomOrder(): Order
    {
        /** @var Order $order */
        $order = Order::inRandomOrder()->first();
        if (!$order) {
            return $this->createOrder();
        }

        return $order;
    }
}
