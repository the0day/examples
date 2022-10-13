<x-panel.wrap>
    @slot('title', __('order.text.id', [$order->id]))
    @slot('description')
        <p class="text-green-500 leading-5">@lang('order.status_description.buyer.'.$order->status)</p>
        @lang('order.text.deadline'): {{$order->deadline_at}}
    @endslot

    <div class="aspect-auto overflow-hidden sm:aspect-none">
        @if ($offer->getFirstPreview())
            <img src="{{$offer->getFirstPreview()->getUrl(App\Enums\MediaConversion::thumb())}}"
                 alt="{{$offer->title}}"
                 class="w-3/4 object-center object-cover m-auto">
        @endif
    </div>
    <div>
        <ul class="text-sm">
            @foreach($options as $optionTitle => $optionValue)
                <li><span class="font-medium text-gray-900">{{$optionTitle}}:</span>
                    <span>{{$optionValue}}</span></li>
            @endforeach
        </ul>

        <div class="mt-2 bg-yellow-50 border-yellow-200 border-solid border rounded p-2">
            <h3 class="font-medium">@lang('order.text.note_to_seller'):</h3>
            <p class="leading-5">{{$order->note_to_seller ?? ""}}</p>
            <livewire:uploaded-media :items="$order->getMedia(App\Enums\MediaCollectionType::orderSample())"/>
        </div>
    </div>
</x-panel.wrap>