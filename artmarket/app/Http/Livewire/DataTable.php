<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class DataTable extends LivewireDatatable
{
    use AuthorizesRequests;

    public $model = Order::class;

    public function boot()
    {
        if (Auth::guest()) {
            abort(403);
        }
    }
}
