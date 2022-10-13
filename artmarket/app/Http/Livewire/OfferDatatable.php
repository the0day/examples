<?php

namespace App\Http\Livewire;

use App\Models\Offer;
use App\View\LivewireTable\Column;
use App\View\LivewireTable\Translatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class OfferDatatable extends DataTable
{
    public $model = Offer::class;

    public function columns()
    {
        return [
            NumberColumn::name('id')->label('ID'),
            Translatable::name('title')->label(__("offer.field.title")),
            NumberColumn::name('likes')->label(__("offer.field.likes")),
            NumberColumn::name('orders.id:count')->label(__("offer.field.orders")),
            Column::edit('id', 'account.offers.edit')->alignRight()
        ];
    }
}
