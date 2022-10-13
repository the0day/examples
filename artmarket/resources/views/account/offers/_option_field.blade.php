@php
    /** @var OfferOption $existedOption */
    use App\Models\OfferOption;
    use Cknow\Money\Money;
    use Money\Formatter\DecimalMoneyFormatter;

    $oldDays = old('options.'.$id.'.fields.'.$field_id.'.days');
    $oldPrice = old('options.'.$id.'.fields.'.$field_id.'.price');

    $input = "options[$id][fields][$field_id]";
    $blockId = "bo{$id}_{$field_id}";
    $isActive = !is_null($existedOption) || $errors->hasAny(["options.{$option->id}.fields.".$field_id.".*"]) || $oldPrice || $oldDays;
    $days = $existedOption ? $existedOption->getDays($field_id) : '';
    $price = $existedOption && $existedOption->getPrice($field_id)
        ? $existedOption->getPrice($field_id)->formatByDecimal()
        : $oldPrice;
@endphp

<div class="flex py-1 items-center justify-self-end"
     x-data="{ {{$blockId}}: {{$field_id =='0' ? 'true' : ($isActive ? "true" : "false")}}}">
    @if(!empty($value))
        <div class="flex-1">
            <x-form-checkbox
                    class="h-5 w-5"
                    name="{!! $input !!}[name]"
                    value="{{$field_id}}"
                    :label="$value"
                    x-model="{{$blockId}}"
                    @click="{{$blockId}} = ! {{$blockId}}"
                    hide-errors="true"/>
        </div>
    @endif
    <div class="flex text-sm items-center justify-self-end" x-show="{{$blockId}}">
        <span class="mr-2">@lang('offer_option.text.delivery')</span>
        <x-form-input :value="$existedOption ? $existedOption->getDays($field_id) : $oldDays"
                      class="w-9 h-6 px-1 text-sm"
                      id="{{$field_id}}" name="{!! $input !!}[days]" min="0" max="90" :show-errors="false"/>
        <span class="ml-2 mr-2">@lang('offer_option.text.days'), @lang('offer_option.text.extra')</span>
        <x-form-input :value="$price" id="{{$field_id}}" name="{!! $input !!}[price]" class="w-16 h-6 px-1" type="text"
                      :show-errors="false">
            @slot('prepend')
                $
            @endslot
        </x-form-input>
    </div>
</div>
