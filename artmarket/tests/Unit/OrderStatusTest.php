<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use App\Models\Offer;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatus\AcceptedBuyer;
use App\Notifications\OrderStatus\AcceptedSeller;
use App\Notifications\OrderStatus\AcceptingBuyer;
use App\Notifications\OrderStatus\AcceptingSeller;
use App\Notifications\OrderStatus\ApprovingBuyer;
use App\Notifications\OrderStatus\ApprovingSeller;
use App\Notifications\OrderStatus\Cancelled;
use App\Notifications\OrderStatus\CancelledAcceptingBuyer;
use App\Notifications\OrderStatus\CancelledAcceptingSeller;
use App\Notifications\OrderStatus\CancelledPayment;
use App\Notifications\OrderStatus\CancellingBuyer;
use App\Notifications\OrderStatus\CancellingSeller;
use App\Notifications\OrderStatus\DeclinedBuyer;
use App\Notifications\OrderStatus\DeclinedSeller;
use App\Notifications\OrderStatus\DeliveredBuyer;
use App\Notifications\OrderStatus\DeliveredSeller;
use App\Notifications\OrderStatus\Payment;
use Assert\InvalidArgumentException;
use Cknow\Money\Money;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{

    /**
     * @dataProvider getOrderStatusesTransits
     * @throws Exception
     */
    public function testBunch(...$actions)
    {
        Notification::fake();
        $offer = Offer::factory(['price' => Money::USD(1000)])->create();
        $order = $this->checkout($offer);

        foreach ($actions as $i => $data) {
            $actions_ex = explode("|", $data[0]);
            $action = $actions_ex[0];
            $assertStatus = $data[1];
            $notifications = $data[2] ?? [];
            $actionAs = isset($actions_ex[1]) ? $order->{$actions_ex[1]} : $order->seller;
            $expectExeption = $data[3] ?? null;

            try {
                $this->{$action}($order, $actionAs);
            } catch (Exception $exception) {

                if ($expectExeption) {
                    $this->assertInstanceOf($expectExeption, $exception);
                } else {
                    throw $exception;
                }
            }

            $this->assertOrderStatus($order, $assertStatus, $i . ': ' . $data[0]);

            foreach ($notifications as $field => $notificationType) {
                Notification::assertSentTo($order->{$field}, $notificationType);
            }
        }
    }

    private function assertOrderStatus(Order $order, OrderStatus $status, string $message = null)
    {
        $this->assertEquals($status, $order->status, $message ?? 'Order must have ' . $status . ' status');
    }

    private function checkout(Offer $offer): Order
    {
        $order = $this->checkoutService->create($this->createUser(), $offer);
        $this->assertOrderStatus($order, OrderStatus::payment());
        Notification::assertSentTo($order->buyer, Payment::class);

        return $order;
    }

    private function payment(Order $order): void
    {
        $this->userService->addCredit($order->buyer, $order->total_cost);
        $this->orderService->doPayment($order);
        $this->assertOrderStatus($order, OrderStatus::accepting());
        Notification::assertSentTo($order->buyer, AcceptingBuyer::class);
        Notification::assertSentTo($order->seller, AcceptingSeller::class);
    }


    private function approving(Order $order, User $user): void
    {
        $final = UploadedFile::fake()->image('avatar.jpg', 123, 66);
        $this->orderService->processUploadWork($order, $order->seller, [], $final);
    }

    public function accept(Order $order, User $user)
    {
        $this->orderStatusService->accept($user, $order);
    }

    public function decline(Order $order, User $user)
    {
        $this->orderStatusService->decline($user, $order);
    }

    private function cancel(Order $order, User $user): void
    {
        $this->orderStatusService->cancel($user, $order);
    }

    public function getOrderStatusesTransits(): array
    {
        $delivered = $this->ntfData(DeliveredBuyer::class, DeliveredSeller::class);
        $payment = $this->ntfData(Payment::class);
        $canceledPayment = $this->ntfData(CancelledPayment::class);
        $cancelling = $this->ntfData(CancellingBuyer::class, CancellingSeller::class);
        $cancelled = $this->ntfData(Cancelled::class, Cancelled::class);
        $cancelledAccepting = $this->ntfData(CancelledAcceptingBuyer::class, CancelledAcceptingSeller::class);
        $approving = $this->ntfData(ApprovingBuyer::class, ApprovingSeller::class);
        $accepted = $this->ntfData(AcceptedBuyer::class, AcceptedSeller::class);
        $declined = $this->ntfData(DeclinedBuyer::class, DeclinedSeller::class);

        $pay = ['payment', OrderStatus::accepting(), $payment];
        $accept = ['accept|seller', OrderStatus::accepted(), $accepted];
        $toApprove = ['approving|seller', OrderStatus::approving(), $approving];
        $declineWork = ['decline|buyer', OrderStatus::declined(), $declined];

        return [
            'decline-without-payment' => [
                ['decline|buyer', OrderStatus::cancelled(), $canceledPayment]
            ],
            'cancel-without-payment'  => [
                ['cancel|buyer', OrderStatus::cancelled(), $canceledPayment]
            ],

            'accepting-cancel-as-buyer'  => [
                $pay,
                ['cancel|buyer', OrderStatus::cancelled(), $cancelledAccepting]
            ],
            'accepting-decline-as-buyer' => [
                $pay,
                ['decline|buyer', OrderStatus::cancelled(), $cancelledAccepting]
            ],
            'accepting-cancel-as-seller' => [
                $pay,
                ['cancel|buyer', OrderStatus::cancelled(), $cancelledAccepting]
            ],
            'accept-as-buyer'            => [
                $pay,
                ['accept|buyer', OrderStatus::accepting(), null, AuthorizationException::class]
            ],

            'accepted-cancel-as-seller'  => [
                $pay, $accept,
                ['cancel|seller', OrderStatus::cancelling(), $cancelling]
            ],
            'accepted-decline-as-seller' => [
                $pay, $accept,
                ['decline|seller', OrderStatus::cancelling(), $cancelling]
            ],
            'accepted-decline-as-buyer'  => [
                $pay, $accept,
                ['decline|buyer', OrderStatus::cancelling(), $cancelling]
            ],
            'accepted-upload-as-buyer'   => [
                $pay, $accept,
                ['accept|buyer', OrderStatus::accepted(), null, AuthorizationException::class]
            ],

            'accepted-upload-as-seller-without-image' => [
                $pay, $accept,
                ['accept|seller', OrderStatus::accepted(), null, InvalidArgumentException::class]
            ],

            'approve-work-as-seller-and-buyer' => [
                $pay, $accept, $toApprove,
                ['accept|seller', OrderStatus::approving(), null, AuthorizationException::class],
                ['decline|seller', OrderStatus::approving(), null, AuthorizationException::class],
                ['accept|buyer', OrderStatus::delivered(), $delivered],
            ],

            'approving-cancel-as-seller' => [
                $pay, $accept, $toApprove,
                ['accept|seller', OrderStatus::approving(), null, AuthorizationException::class],
                ['decline|seller', OrderStatus::approving(), null, AuthorizationException::class],
                ['cancel|seller', OrderStatus::cancelling(), $cancelling],
                ['accept|buyer', OrderStatus::cancelled(), $cancelled],
            ],

            'cancelling-confirm-as-seller' => [
                $pay, $accept, $toApprove,
                ['cancel|buyer', OrderStatus::cancelling(), $cancelling],
                ['accept|buyer', OrderStatus::cancelling(), null, AuthorizationException::class],
                ['decline|buyer', OrderStatus::cancelling(), null, AuthorizationException::class],
                ['cancel|buyer', OrderStatus::cancelling(), null, AuthorizationException::class],
                ['accept|seller', OrderStatus::cancelled(), $cancelled],
            ],
            'cancelling-decline-as-seller' => [
                $pay, $accept, $toApprove,
                ['cancel|buyer', OrderStatus::cancelling(), $cancelling],
                ['accept|buyer', OrderStatus::cancelling(), null, AuthorizationException::class],
                ['decline|buyer', OrderStatus::cancelling(), null, AuthorizationException::class],
                ['cancel|buyer', OrderStatus::cancelling(), null, AuthorizationException::class],
                ['cancel|seller', OrderStatus::cancelling(), null, null],
                ['decline|seller', OrderStatus::accepted()],
            ],

            'declined-accept' => [
                $pay, $accept, $toApprove, $declineWork,
                ['accept|buyer', OrderStatus::declined(), null, AuthorizationException::class],
                ['decline|buyer', OrderStatus::declined(), null, AuthorizationException::class],
                ['accept|seller', OrderStatus::accepted()],
            ],
        ];
    }

    private function ntfData(string $buyer = null, string $seller = null): array
    {
        if ($buyer) {
            $data['buyer'] = $buyer;
        }

        if ($seller) {
            $data['seller'] = $seller;
        }

        return $data;
    }
}