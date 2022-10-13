<?php

namespace App\Services;


use App\Enums\MediaCollectionType;
use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use Assert\Assertion;
use Assert\AssertionFailedException;
use File;
use Gate;
use Illuminate\Support\Collection;

class ChatService
{
    /**
     * @param Order $order
     * @param User $user
     * @return Collection
     * @throws AssertionFailedException
     */
    public function getMessages(Order $order, User $user): Collection
    {
        Assertion::true(Gate::forUser($user)->allows('view', $order));

        return $order->messages;
    }

    /**
     * @param Order $order
     * @param User $user
     * @param string $message
     * @param array|null $images
     * @return Message
     * @throws AssertionFailedException
     */
    public function sendMessage(Order $order, User $user, string $message, ?array $images = []): Message
    {
        Assertion::true(Gate::forUser($user)->allows('view', $order));

        $message = $order->messages()->create([
            'user_id' => $user->id,
            'body'    => $message
        ]);

        /** @var File $upload */

        if (is_array($images)) {
            foreach ($images as $image) {
                MediaService::attach($message, $user, $image, MediaCollectionType::message());
            }
        }

        return $message;
    }
}