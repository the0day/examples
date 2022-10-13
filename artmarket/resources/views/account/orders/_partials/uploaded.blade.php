<?php
/**
 * @var Order $order
 */

use App\Models\Order;

?>
@if ($order->getSketchMedia()->isNotEmpty() || $order->getFinalMedia()->isNotEmpty())
    <x-panel.wrap>
        @slot('title', __('order.text.uploaded_works'))

        <div>
            @if ($order->getSketchMedia()->isNotEmpty())
                <x-form.label>@lang('order.text.sketch')</x-form.label>
                <x-display.uploaded-media :items="$order->getSketchMedia()"/>
            @endif

            @if ($order->getFinalMedia()->isNotEmpty())
                <x-form.label>@lang('order.text.final')</x-form.label>
                <x-display.uploaded-media :items="$order->getFinalMedia()"/>
            @endif
        </div>
    </x-panel.wrap>
@endif