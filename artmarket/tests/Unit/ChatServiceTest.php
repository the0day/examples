<?php

namespace Tests\Unit;

use App\Models\Message;
use Assert\AssertionFailedException;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tests\Traits\OfferTrait;
use Tests\Traits\OrderTrait;

class ChatServiceTest extends TestCase
{
    use OfferTrait, OrderTrait;

    public function testUsersCanReadMessages()
    {
        $thirdUser = $this->createUser();
        $order = $this->prepareOrder();
        $this->createMessagesAtOrder($order, $messagesCount = 5);

        $messagesBuyer = $this->chatService->getMessages($order, $order->buyer);
        $messagesSeller = $this->chatService->getMessages($order, $order->seller);

        $this->assertCount($messagesCount, $messagesBuyer);
        $this->assertEquals($messagesBuyer, $messagesSeller);

        $this->expectException(AssertionFailedException::class);
        $this->chatService->getMessages($order, $thirdUser);
    }

    public function testTheParticipantsCanSendMessages()
    {
        $order = $this->prepareOrder();
        $messages = $this->chatService->getMessages($order, $order->buyer);
        $this->assertCount(0, $messages);

        $this->chatService->sendMessage($order, $order->buyer, $message = 'test message');
        $this->chatService->sendMessage($order, $order->seller, $message2 = 'test message 2');
        $order->refresh();

        $this->assertDatabaseHas('messages', [
            'body'     => $message,
            'user_id'  => $order->user_id,
            'order_id' => $order->id
        ]);
        $this->assertDatabaseHas('messages', [
            'body'     => $message2,
            'user_id'  => $order->seller_id,
            'order_id' => $order->id
        ]);

        $this->assertCount(2, $this->chatService->getMessages($order, $order->buyer));
        $this->assertCount(2, $this->chatService->getMessages($order, $order->seller));
    }

    public function testMessagesWithImages()
    {
        $order = $this->prepareOrder();
        $this->actingAs($order->buyer);

        $images = [
            UploadedFile::fake()->image('img1.jpg', 100, 200),
            UploadedFile::fake()->image('img2.jpg', 110, 210),
            UploadedFile::fake()->image('img3.jpg', 120, 220),
        ];
        $this->chatService->sendMessage($order, $order->buyer, 'test message', $images);

        /** @var Message $message */
        $message = $order->messages()->first();
        $images = $message->getImages();
        $this->assertCount(3, $images);
    }
}