<?php
/**
 * @var $option OfferOption
 */

use App\Models\OfferOption;

?>
@empty($option->field_values)
    <div class="flex justify-between mb-2">
        @include('offers.fields.'.$option->glossary->field_type->getKey())

        @if ($option->price)
            <div class="text-right">
                <span class="font-bold text-green-500">+{{$option->price}}</span>
            </div>
        @endif
    </div>
@else
    <span class="font-medium text-sm leading-none font-bold">{{$option->glossary->title}}</span>
    <x-form-select
            name="option['{{$option->id}}']"
            :options="$option->getFieldsForSelect()"
            class="mb-2"/>
@endempty
