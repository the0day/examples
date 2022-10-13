<?php

namespace Tests\Feature\Livewire;

use App\Enums\OrderStatus;
use App\Http\Livewire\OrderDatatable;
use Livewire\Livewire;
use Tests\TestCase;

class OrderDatatableTest extends TestCase
{
    public function testMyOrderOnlyForUsers()
    {
        Livewire::test(OrderDatatable::class)
            ->assertStatus(403);

        $this->actingAs($this->createUser());
        Livewire::test(OrderDatatable::class)
            ->assertStatus(200);
    }

    /**
     * The user must receive table with specific amount of columns and title of columns
     */
    public function testMyPurchasesList()
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $order = $this->createOrderDirectly(['status' => OrderStatus::accepting()], null, $user);
        $order2 = $this->createOrderDirectly(['status' => OrderStatus::accepting()], null, $user);

        $datatable = Livewire::test(OrderDatatable::class)
            ->assertSeeText($order->title)
            ->assertSeeText($order2->title);

        $this->assertIsArray($datatable->columns);
        $this->assertEquals([
            0 => 'ID',
            1 => __("order.text.order_placed_at"),
            2 => __("offer.field.title"),
            3 => __("order.field.buyer"),
            4 => __('order.text.status'),
            5 => null,
        ], collect($datatable->columns)->map->label->toArray());
    }
}