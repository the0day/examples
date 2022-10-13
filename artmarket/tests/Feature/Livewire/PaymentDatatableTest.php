<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\PaymentDatatable;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentDatatableTest extends TestCase
{
    public function testMyPaymentsOnlyForUsers()
    {
        Livewire::test(PaymentDatatable::class)
            ->assertStatus(403);

        $this->actingAs($this->createUser());
        Livewire::test(PaymentDatatable::class)
            ->assertStatus(200);
    }

    public function testMyPayments()
    {
        $user = $this->createUser();
        $this->actingAs($this->createUser());

        $datatable = Livewire::test(PaymentDatatable::class)
            ->assertStatus(200);

        $this->actingAs($user);

        $this->assertIsArray($datatable->columns);
        $this->assertEquals([
            0 => 'ID',
            1 => __('payment.field.payment_at'),
            2 => __("payment_method.singular"),
            3 => __("payment.field.amount"),
            4 => __('payment.field.status'),
        ], collect($datatable->columns)->map->label->toArray());
    }
}