<x-panel.wrap :title="$optionGroup->title">
    <div>
        <div class="px-4 uppercase text-gray-900"></div>
        <fieldset class="mt-3 md:md-5 pb-3 md:pb-5 grid grid-cols-8 md:gap-4 gap-1 px-4 md:py-3 p">
            @foreach($optionGroup->options as $option)
                @php
                    /** @var App\Models\OfferOption $existedOption */
                    $existed = $options->where('option_id', '=', $option->id)->first();
                    $value = $existed ? $existed->name : '';
                @endphp
                <div class="md:col-span-2 sm:col-span-3 col-span-8 self-center">
                    <x-form.label>{{$option->title}}</x-form.label>
                </div>
                <div class="md:col-span-6 sm:col-span-5 col-span-8">
                    <x-offer-option :option="$option" :value="$value" name="options[{{$option->id}}][name]"/>
                </div>
            @endforeach
        </fieldset>
    </div>
</x-panel.wrap>
