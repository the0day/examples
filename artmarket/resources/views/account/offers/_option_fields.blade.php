<?php
/**
 * @var Option $option
 * @var Offer $offer
 */

use App\Models\Glossary\Option;
use App\Models\Offer;

?>
<x-panel.wrap :title="$optionGroup->title">
    <ul class="divide-y mb-4">
        @foreach($optionGroup->options as $option)
            @php
                $blockId = "bo".$option->id;
                $existed = $options->where('option_id', '=', $option->id)->first();
                $isActive = !is_null($existed) || old('options.'.$option->id.'.name') || $errors->hasAny(["options.{$option->id}.*"]);
            @endphp
            <li class="text-gray-600 {{$errors->hasAny(["options.{$option->id}.*"]) ? 'border-l-2 border-red-500' : ''}}"
                x-data="{ {{$blockId}}: {{$isActive ? 'true': 'false'}} }">
                <div class="px-4 pr-12 flex justify-between items-center">
                    <div class="flex-none my-3">
                        <x-form-checkbox
                                class="h-5 w-5"
                                name="options[{{$option->id}}][name]"
                                :label="$option->title"
                                :value="$option->alias"
                                x-model="{{$blockId}}"
                                @click="{{$blockId}} = ! {{$blockId}}"
                                hide-errors="true"/>


                        @foreach($errors->get("options.{$option->id}.*") as $messages)
                            @if (is_array($messages))
                                @foreach($messages as $message)
                                    <div class="text-red-600 text-xs">{{$message}}</div>
                                @endforeach
                            @else
                                <div class="text-red-600 text-xs">{{$messages}}</div>
                            @endif

                        @endforeach

                    </div>
                    @if(!$option->hasFieldValues())
                        <div x-show="{{$blockId}}">
                            @include('account.offers._option_field', ['id' => $option->id, 'value' => null, 'field_id' => '0', 'existedOption' => $existed])
                        </div>
                    @endif
                </div>

                @if($option->hasFieldValues())
                    <div x-show="{{$blockId}}" class="bg-gray-50 px-12">
                        @foreach($option->field_values as $key => $value)
                            @include('account.offers._option_field', ['id' => $option->id, 'field_id' => $key, 'existedOption' => $existed])
                        @endforeach
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
</x-panel.wrap>
