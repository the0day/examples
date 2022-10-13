<?php

namespace App\Services\Order;

use App\Enums\OrderStatus;
use App\Enums\OrderStatus as OrderStatusType;
use App\Exceptions\UnauthorizedStatusChange;
use App\Models\Order;
use App\Models\User;
use App\Services\Order\Statuses\Accepted;
use App\Services\Order\Statuses\Accepting;
use App\Services\Order\Statuses\Approving;
use App\Services\Order\Statuses\Cancelled;
use App\Services\Order\Statuses\Cancelling;
use App\Services\Order\Statuses\Declined;
use App\Services\Order\Statuses\Delivered;
use App\Services\Order\Statuses\Payment;
use App\Services\Order\Statuses\Status;
use Assert\AssertionFailedException;
use RuntimeException;

class OrderStatusContext
{
    protected Order $order;
    protected ?User $user;
    private Status $currentStatus;

    public function __construct(Order $order, User $user = null)
    {
        $this->order = $order;
        $this->user = $user;
        $this->setCurrentStatus();
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setCurrentStatus()
    {
        $this->currentStatus = self::factory($this->order->status);
    }

    /**
     * @throws AssertionFailedException
     * @throws UnauthorizedStatusChange
     */
    public function accept()
    {
        $this->validation();
        if ($this->currentStatus->canAccept($this)) {
            $this->currentStatus->accept($this);
        }
    }

    public function decline()
    {
        $this->validation();
        if ($this->currentStatus->canDecline($this)) {
            $this->currentStatus->decline($this);
        }
    }

    public function cancel()
    {
        $this->validation();
        if ($this->currentStatus->canCancel($this)) {
            $this->currentStatus->cancel($this);
        }
    }

    /**
     * @throws UnauthorizedStatusChange
     */
    private function validation(): void
    {
        if (!$this->currentStatus->authorized($this)) {
            throw new UnauthorizedStatusChange(__('order.error.cant_change_status') . ': ' . $this->currentStatus::class);
        }
    }

    static public function factory(OrderStatusType $status): Status
    {
        switch ($status) {
            case OrderStatusType::payment():
                return new Payment();

            case OrderStatusType::accepting():
                return new Accepting();
        }

        return match ($status) {
            OrderStatusType::payment() => new Payment(),
            OrderStatusType::accepting() => new Accepting(),
            OrderStatusType::accepted() => new Accepted(),
            OrderStatusType::approving() => new Approving(),
            OrderStatusType::cancelled() => new Cancelled(),
            OrderStatusType::cancelling() => new Cancelling(),
            OrderStatusType::delivered() => new Delivered(),
            OrderStatusType::declined() => new Declined(),
            default => throw new RuntimeException('Invalid order status'),
        };
    }
}