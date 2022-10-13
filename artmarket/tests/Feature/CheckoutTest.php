<?php

namespace Tests\Feature;

use App\Models\Offer;
use App\Models\Order;
use Cknow\Money\Money;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    /** @test */
    public function testUserCanCheckoutAnOffer()
    {
        $offer = Offer::factory(['price' => Money::USD(1000)])->create();
        $customer = $this->createUser();

        // Create an order
        // check: orders count
        $this->checkoutService->create($customer, $offer);

        $this
            ->assertGuest()
            ->get(route('offer.checkout.view', [$offer->user->name, $offer->alias]))
            ->assertStatus(302);

        $this
            ->actingAs($offer->user)
            ->get(route('offer.checkout.view', [$offer->user->name, $offer->alias]))
            ->assertStatus(404);

        $this
            ->actingAs($customer)
            ->get(route('offer.checkout.view', [$offer->user->name, $offer->alias]))
            ->assertStatus(200);
    }

    public function testOrderDeadlineField()
    {
        $offer = Offer::factory(['price' => Money::USD(1000)])->create();
        $customer = $this->createUser();
        $this->actingAs($customer);

        $order = $this->checkoutService->create($customer, $offer);
        $this->userService->addCredit($customer, $order->total_cost);


        $url = route('offer.checkout.payment', [$offer->user->name, $offer->alias]);

        $this->post($url, [])
            ->assertInvalid(
                ['deadline' => __('validation.required',
                    ['attribute' => __('validation.attributes.deadline')])]
            );

        $this->post($url, ['deadline' => date("Y/m/d", strtotime('+2 days'))])
            ->assertInvalid(['deadline' => __('validation.date_format', ['attribute' => __('validation.attributes.deadline'), 'format' => 'd/m/Y'])]);

        $this->post($url, ['deadline' => date("d/m/Y", strtotime('-2 days'))])
            ->assertInvalid(['deadline' => __('validation.after', ['attribute' => __('validation.attributes.deadline'), 'date' => 'tomorrow'])]);

        $this->post($url, ['deadline' => date("d/m/Y", strtotime('+31 days'))])
            ->assertInvalid(['deadline' => __('validation.before', ['attribute' => __('validation.attributes.deadline'), 'date' => '+30 days'])]);

        $date = date("d/m/Y", strtotime('+2 days'));

        $this->post($url, [
            'deadline'       => $date,
            'payment_method' => $this->defaultPaymentGateway()
        ])
            ->assertStatus(200);

        $order = Order::whereOfferId($offer->id)
            ->whereUserId($customer->id)
            ->first();

        $this->assertNotNull($order, 'There is no order');
        $this->assertEquals($date, $order->deadline_at->format('d/m/Y'));
    }

    public function testOrderPayment()
    {
        $offer = Offer::factory(['price' => Money::USD(1000)])->create();
        $customer = $this->createUser();
        $this->actingAs($customer);

        $order = $this->checkoutService->create($customer, $offer);
        $this->userService->addCredit($customer, $order->total_cost);
        $url = route('offer.checkout.payment', [$offer->user->name, $offer->alias]);

        $data = [
            'deadline'       => date("d/m/Y", strtotime('+14 days')),
            'notes'          => 'test description',
            'payment_method' => $this->defaultPaymentGateway(),
            'image'          => [
                '0' => UploadedFile::fake()->image('image-1.jpg', 64, 64),
                '1' => UploadedFile::fake()->image('image-2.jpg', 32, 32),
            ]
        ];
        $this->post($url, $data)
            ->assertStatus(200);

        $order = Order::whereOfferId($offer->id)
            ->whereUserId($customer->id)
            ->first();

        $this->assertEquals($data['notes'], $order->note_to_seller);
        $this->assertEquals($data['deadline'], $order->deadline_at->format('d/m/Y'));
        $this->assertCount(count($data['image']), $order->getSampleMedia());
    }
}
