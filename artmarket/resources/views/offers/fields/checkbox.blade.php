<div class="text-left flex">
    <div class="w-9 text-center mr-2">
        <input
                class="h-5 w-5 justify-self-center rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50"
                type="checkbox"
                value="1"
                name="option['{{$option->id}}']">
    </div>
    {{ $option->glossary->title.' '.($option->days > 0 ? '('.$option->days.' '.trans('offer_option.text.days_short').')' : '') }}
</div>




