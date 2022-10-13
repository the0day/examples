<?php

namespace App\Http\Controllers;


use App\DTO\Order\OrderOptionsCollection;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\OrderPaymentRequest;
use App\Http\Requests\OrderSampleRequest;
use App\Http\Resources\OrderResource;
use App\Models\Offer;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Services\CheckoutService;
use App\Services\OrderService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class OfferController extends Controller
{
    public OrderService $orderService;
    public CheckoutService $checkoutService;

    public function __construct(OrderService $orderService, CheckoutService $checkoutService)
    {
        $this->orderService = $orderService;
        $this->checkoutService = $checkoutService;
    }

    public function view(User $user, Offer $offer)
    {
        $this->authorizeForUser($user, 'owner', $offer);

        return view('offers.view')
            ->with('offer', $offer->load('options', 'options.glossary'));
    }

    /**
     * Формирование заказа и редирект на страницу оплаты
     *
     * @param CheckoutRequest $request
     * @param User $user
     * @param Offer $offer
     * @return OrderResource|Application|Factory|View
     */
    public function order(CheckoutRequest $request, User $user, Offer $offer)
    {
        $offer->load('options', 'options.glossary');

        $order = $this->checkoutService->create(
            $this->user(),
            $offer,
            OrderOptionsCollection::fromArray($request->input('option')),
            !$request->has('calculate')
        );

        if ($request->has('calculate')) {
            return new OrderResource($order);
        }

        return view('checkout.index')
            ->with('offer', $offer)
            ->with('order', $order)
            ->with('paymentGateways', PaymentMethod::active()->get());
    }

    /**
     * Страница оплаты
     *
     * @param User $user
     * @param Offer $offer
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function checkout(User $user, Offer $offer)
    {
        $order = $this->orderService->getFirstOrder($this->user(), $offer);
        $this->authorize('buyer', $order);

        $paymentGateways = PaymentMethod::active()->get();

        return view('checkout.index')
            ->with('offer', $offer)
            ->with('order', $order)
            ->with('paymentGateways', $paymentGateways);
    }

    /**
     * Оплата заказа
     *
     * @param User $user
     * @param Offer $offer
     * @param OrderPaymentRequest $request
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function payment(User $user, Offer $offer, OrderPaymentRequest $request)
    {
        $buyer = $this->user();
        $order = $this->orderService->getFirstOrder($buyer, $offer);
        $this->authorize('buyer', $order);

        $this->orderService->processCheckout($order, $buyer, $request);

        $paymentGateways = PaymentMethod::active()->get();

        return view('checkout.index')
            ->with('offer', $offer)
            ->with('order', $order)
            ->with('paymentGateways', $paymentGateways);
    }
}
