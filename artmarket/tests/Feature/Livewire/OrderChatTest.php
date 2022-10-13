<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\OrderChat;
use Livewire\Livewire;
use Tests\TestCase;

class OrderChatTest extends TestCase
{
    public function testOrderChatOnlyForUsers()
    {
        Livewire::test(OrderChat::class)
            ->assertStatus(403);

        $order = $this->createOrderDirectly();
        $this->actingAs($order->buyer);

        Livewire::test(OrderChat::class, ['order' => $order])
            ->assertStatus(200);
    }

    public function testComponentCanRender()
    {
        $order = $this->createOrderDirectly();
        $this->actingAs($order->buyer);

        $livewire = Livewire::test(OrderChat::class, ['order' => $order])
            ->assertStatus(200)
            ->call('reply')
            ->assertHasErrors(['text' => 'required'])
            ->set('text', 'qw')
            ->call('reply')
            ->assertHasErrors(['text' => 'min:3'])
            ->set('text', 'test message')
            ->call('reply')
            ->assertHasNoErrors();

        $instance = $livewire->instance();
        $this->assertCount(1, $instance->messages);
    }
}
