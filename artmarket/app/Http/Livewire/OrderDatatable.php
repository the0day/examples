<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\View\LivewireTable\Column;
use App\View\LivewireTable\Translatable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

class OrderDatatable extends DataTable
{
    use AuthorizesRequests;

    public $model = Order::class;

    public function columns()
    {
        return [
            NumberColumn::name('id')->label('ID')->linkTo('order'),
            DateColumn::name('created_at')->label(__("order.text.order_placed_at")),
            Translatable::name('offer.title')->label(__("offer.field.title")),
            Column::name('buyer.name')->label(__("order.field.buyer")),
            Column::callback(['status'], function (string $status) {
                return __('order.status.' . $status);
            })->label(__('order.text.status')),
            Column::show('account.orders.view')
        ];
    }
}
