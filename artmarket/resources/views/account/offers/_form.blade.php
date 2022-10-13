<?php
/**
 * @var OfferType $offerType
 * @var Offer $offer
 */

use App\Models\Glossary\OfferType;
use App\Models\Offer;

$url = route('offer.view', [auth()->user()->name, '']);
$optionGroups = $offerType->optionGroups;
$options = $offer ? $offer->options : collect();
?>

<x-form.hidden value="{{$offerType->id}}" id="offer_type_id"/>

<x-panel.wrap title="{{__('account.offers.fields.image')}}">
    <x-panel.body>
        @if ($offer)
            <livewire:uploaded-media :items="$offer->getMedia(App\Enums\MediaCollectionType::offer())"/>
        @endif
        <livewire:image-upload/>
    </x-panel.body>
</x-panel.wrap>

<x-panel.wrap title="{{__('account.offers.base_information')}}">
    <x-panel.body class="grid grid-cols-4 gap-y-4 gap-x-6">
        <div class="col-span-5">
            <x-errors/>
        </div>

        <div class="col-span-5">
            <x-form-input name="title" id="title" label="{{__('account.offers.fields.title')}}"/>
        </div>

        <div class="col-span-5">
            <x-form-input name="url" id="url" label="{{__('account.offers.fields.url')}}">
                @slot('prepend')
                    {{$url}}/
                @endslot
            </x-form-input>
        </div>

        <div class="col-span-5">
            <x-form-textarea name="description" rows="5" label="{{__('account.offers.fields.description')}}"/>
        </div>

        <div class="col-span-1">
            @isset($offer)
                <x-form-input name="price" id="price" label="{{__('account.offers.fields.price')}}"
                              :value="$offer->price?->formatByDecimal()">
                    @slot('prepend')
                        $
                    @endslot
                </x-form-input>
            @else
                <x-form-input name="price" id="price" label="{{__('account.offers.fields.price')}}">
                    @slot('prepend')
                        $
                    @endslot
                </x-form-input>
            @endisset

        </div>

        <div class="col-span-2">
            <x-form-select
                    label="{{__('account.offers.fields.category_id')}}"
                    name="category_ids[]"
                    :options="$categories"/>
        </div>
    </x-panel.body>
</x-panel.wrap>


@foreach($optionGroups as $optionGroup)
    @includeWhen($optionGroup->type->isInfo(), 'account.offers._option_info_fields', ['optionGroup' => $optionGroup, 'options' => $options])
@endforeach

@foreach($optionGroups as $optionGroup)
    @includeWhen(!$optionGroup->type->isInfo(), 'account.offers._option_fields', ['optionGroup' => $optionGroup, 'options' => $options])
@endforeach

<x-form-submit/>
