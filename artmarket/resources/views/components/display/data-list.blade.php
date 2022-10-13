<div {{ $attributes->merge(['class' => 'border-t border-gray-200']) }}>
    <dl class="sm:divide-y sm:divide-gray-200">
        @foreach($items as $label => $item)
            <x-display.data-item
                    :label="$label"
                    :value="$item"
                    class="{{$attributes->get('item-class', 'py-2 sm:py-3')}}"/>
        @endforeach
    </dl>
</div>
