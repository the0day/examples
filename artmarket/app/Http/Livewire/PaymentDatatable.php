<?php

namespace App\Http\Livewire;

use App\Models\Payment;
use App\View\LivewireTable\Column;
use App\View\LivewireTable\Translatable;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

class PaymentDatatable extends DataTable
{
    public $model = Payment::class;

    public function columns()
    {
        return [
            NumberColumn::name('id')->label('ID'),
            DateColumn::name('updated_at')->label(__("payment.field.payment_at")),
            Translatable::name('paymentMethod.title')->label(__("payment_method.singular")),
            Column::callback(['amount', 'currency'], function (float $amount, string $currency) {
                $class = $amount >= 0 ? 'text-green-500' : 'text-red-500';
                $sign = $amount > 0 ? '+' : '-';

                return '<span class="' . $class . '">' . $sign . money($amount, $currency) . '</span>';
            })->label(__("payment.field.amount")),

            Column::name('status')->label(__('payment.field.status')),
        ];
    }
}