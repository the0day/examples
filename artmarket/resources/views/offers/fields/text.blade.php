<div class="text-left flex">
    <div class="w-9 mr-2">
        <x-form-input name="option['_{{$option->id}}']" class="w-full p-1 h-7"/>
    </div>

    {{ $option->glossary->title.' '.($option->days > 0 ? '('.$option->days.' '.trans('offer_option.text.days_short').')' : '') }}
</div>
