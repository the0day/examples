<?php

namespace Tests\Unit\Notifications;

use App\Http\Livewire\OrderChat;
use App\Notifications\ChatMessageReceived;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class ChatMessageReceivedTest extends TestCase
{
    public function testNotificationReceivingFromChat()
    {
        Notification::fake();
        $order = $this->getRandomOrder();
        $this->actingAs($order->buyer);
        $livewire = Livewire::test(OrderChat::class, ['order' => $order]);
        $livewire->call('reply')
            ->set('text', 'test message receiving')
            ->call('reply')
            ->assertHasNoErrors();

        Notification::assertSentTo($order->seller, ChatMessageReceived::class);
    }
}
