<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\OfferDatatable;
use Livewire\Livewire;
use Tests\TestCase;

class OfferDatatableTest extends TestCase
{
    public function testMyOffersOnlyForUsers()
    {
        Livewire::test(OfferDatatable::class)
            ->assertStatus(403);

        $this->actingAs($this->createUser());
        Livewire::test(OfferDatatable::class)
            ->assertStatus(200);
    }

    /**
     * The user must receive table with specific amount of columns and title of columns
     */
    public function testMyOffersList()
    {
        $user = $this->createUser();

        $offer = $this->createOfferWithOptions();
        $this->actingAs($user);

        $datatable = Livewire::test(OfferDatatable::class)
            ->assertSeeText($offer->title);

        $this->assertIsArray($datatable->columns);
        $this->assertEquals([
            0 => 'ID',
            1 => __("offer.field.title"),
            2 => __('offer.field.likes'),
            3 => __('offer.field.orders'),
            4 => null,
        ], collect($datatable->columns)->map->label->toArray());
    }


}