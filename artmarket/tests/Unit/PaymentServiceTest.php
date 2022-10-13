<?php

namespace Tests\Unit;

use Assert\Assertion;
use Tests\TestCase;
use Tests\Traits\OfferTrait;

class PaymentServiceTest extends TestCase
{
    use OfferTrait;

    public function testCheckNoEnoughFunds()
    {
        $order = $this->prepareOrder();
        $this->expectException('Assert\AssertionFailedException');
        $this->expectExceptionCode(Assertion::INVALID_TRUE);
        $this->expectExceptionMessage('no enough funds');

        $this->orderService->doPayment($order);
    }

    public function testCheckOnSitePayment()
    {
        $order = $this->prepareOrder();
        $this->userService->addCredit($customer = $order->buyer, $order->total_cost);

        $this->assertTrue($this->orderService->doPayment($order));
        $this->assertEquals(0, $customer->credit->getAmount());
    }
}
