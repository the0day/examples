<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderAction;
use App\Http\Requests\OrderUploadWork;
use App\Http\Requests\SendMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Order;
use App\Services\ChatService;
use App\Services\NotificationService;
use App\Services\OrderService;
use App\Services\OrderStatusService;
use Auth;

class OrderController extends Controller
{
    public OrderService $orderService;
    public OrderStatusService $orderStatusService;
    public ChatService $chatService;
    public NotificationService $notificationService;

    public function __construct(
        OrderService        $orderService,
        OrderStatusService  $orderStatusService,
        ChatService         $chatService,
        NotificationService $notificationService,
    )
    {
        $this->orderService = $orderService;
        $this->orderStatusService = $orderStatusService;
        $this->chatService = $chatService;
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        return view('account.orders.index')->with('user', $this->user());
    }

    public function purchases()
    {
        return view('account.purchases')->with('user', $this->user());
    }

    public function view(Order $order)
    {
        $this->authorize('view', $order);

        if (!$order->isPaid()) {
            return redirect(route('offer.checkout.view', [$order->seller->name, $order->offer->alias]));
        }

        $this->notificationService->deleteNotificationsFromChat($this->user(), $order);

        return view('account.orders.view')
            ->with('order', $order);
    }

    public function sendMessage(Order $order, SendMessageRequest $request)
    {
        $this->authorize('view', $order);
        $message = $this->chatService->sendMessage(
            $order,
            Auth::user(),
            $request->input('message'),
            $request->file('image')
        );

        return $this->success('sent', new MessageResource($message));
    }

    public function uploadWork(Order $order, OrderUploadWork $request)
    {
        $this->authorize('seller', $order);
        $this->orderService->requestUploadWork($order, $this->user(), $request);

        return redirect(route('account.orders.view', [$order->id]));
    }

    public function action(Order $order, OrderAction $request)
    {
        $this->orderStatusService->accept($this->user(), $order);
        return redirect(route('account.orders.view', [$order->id]));
    }

    public function approve(Order $order)
    {
        $this->orderStatusService->decline($this->user(), $order);
        return redirect(route('account.orders.view', [$order->id]));
    }
}
