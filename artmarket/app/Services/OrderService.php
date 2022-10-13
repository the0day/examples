<?php

namespace App\Services;

use App\Enums\MediaCollectionType;
use App\Enums\OrderStatus;
use App\Http\Requests\OrderPaymentRequest;
use App\Http\Requests\OrderUploadWork;
use App\Models\Offer;
use App\Models\Order;
use App\Models\User;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Carbon\Carbon;
use Gate;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\MediaCannotBeDeleted;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;


class OrderService
{
    /**
     * Пометить заказ как "оплаченный"
     * @param Order $order
     * @return bool
     * @throws AssertionFailedException
     * @throws BindingResolutionException
     */
    public function doPayment(Order $order): bool
    {
        Assertion::notNull($buyer = $order->buyer, 'invalid buyer');
        Assertion::true($buyer->hasEnoughCredit($order->total_cost), 'no enough funds');

        app()->make(OrderStatusService::class)->accept($buyer, $order);
        app()->make(UserService::class)->subtractCredit($buyer, $order->total_cost);
        $order->save();
        return true;
        // @todo send notifications after payment
    }

    /**
     * @param User $user
     * @param Offer $offer
     * @return Order
     */
    public function getOrCreate(User $user, Offer $offer): Order
    {
        try {
            $order = $this->getFirstOrder($user, $offer);
        } catch (ModelNotFoundException) {
            $order = new Order();
            $order->user_id = $user->id;
            $order->offer_id = $offer->id;
            $order->seller_id = $offer->user_id;
            $order->status = OrderStatus::payment();
        }

        return $order;
    }

    /**
     * @param Order $order
     * @param User $user
     * @param OrderPaymentRequest $request
     * @return void
     */
    public function processCheckout(Order $order, User $user, OrderPaymentRequest $request): void
    {
        try {
            $this->setOrderDetails($order, $user, $request);
            $this->doPayment($order);
        } catch (Throwable $exception) {
            throw ValidationException::withMessages(['payment_method' => $exception->getMessage()]);
        }
    }

    public function requestUploadWork(Order $order, User $user, OrderUploadWork $request): void
    {
        Gate::authorize('upload', $order);

        $this->processUploadWork($order, $user, $request->file('sketches', []), $request->file('final'));
    }

    public function processUploadWork(Order $order, User $user, array $sketches = [], ?UploadedFile $final = null): void
    {
        if ($sketches) {
            $this->attachSketches($order, $user, $sketches);
        }

        if ($final) {
            $this->attachFinal($order, $user, $final);
            app()->make(OrderStatusService::class)->accept($user, $order);
        }
    }

    public function acceptOrder(Order $order, User $user): void
    {
        Gate::authorize('accept', $order);

    }

    /**
     * Получить существующий Order
     *
     * @param User $user
     * @param Offer $offer
     * @return Order|null
     */
    public function getFirstOrder(User $user, Offer $offer): ?Order
    {
        return Order::payment()
            ->ofUser($user->id)
            ->ofOffer($offer->id)
            ->firstOrFail();
    }

    /**
     * Загрузить примеры работ (массив файлов)
     *
     * @param Order $order
     * @param User $user
     * @param array $files
     * @return void
     */
    public function attachSamples(Order $order, User $user, array $files = []): void
    {
        if (!$files) {
            return;
        }
        foreach ($files as $file) {
            $this->attachSample($order, $user, $file);
        }
    }

    private function attachSketches(Order $order, User $user, array $files): array
    {
        if (!$files) {
            return [];
        }
        $uploaded = [];
        foreach ($files as $file) {
            $uploaded[] = $this->attachSketch($order, $user, $file);
        }

        return $uploaded;
    }

    /**
     * Загрузить пример работы
     *
     * @param Order $order
     * @param UploadedFile $file
     * @return Media
     */
    public function attachSample(Order $order, User $user, UploadedFile $file): Media
    {
        return $this->attachMedia($order, $user, $file, MediaCollectionType::orderSample());
    }

    public function attachSketch(Order $order, User $user, UploadedFile $file): Media
    {
        Gate::forUser($user)->authorize('seller', $order);

        return $this->attachMedia($order, $user, $file, MediaCollectionType::orderSketch());
    }

    public function attachFinal(Order $order, User $user, UploadedFile $file): Media
    {
        Gate::forUser($user)->authorize('seller', $order);

        return $this->attachMedia($order, $user, $file, MediaCollectionType::orderFinal());
    }

    /**
     * @throws MediaCannotBeDeleted
     */
    public function deAttachSample(Order $order, Media $media): void
    {
        $order->deleteMedia($media);
    }


    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    private function attachMedia(Order $order, User $user, UploadedFile $file, MediaCollectionType $type): Media
    {
        return MediaService::attach($order, $user, $file, $type);
    }

    /**
     * Логика запроса обработки платежа
     *
     * @param Order $order
     * @param User $user
     * @param OrderPaymentRequest $request
     * @return void
     */
    private function setOrderDetails(Order $order, User $user, OrderPaymentRequest $request): void
    {
        $order->note_to_seller = $request->input('notes');

        if ($request->hasFile('image')) {
            $this->attachSamples($order, $user, $request->file('image'));
        }

        $order->deadline_at = Carbon::createFromFormat('d/m/Y', $request->deadline);
    }
}
