@if ($value)
    <x-reviews-line :max="5" :current="$value"/>
@else
    <span class="leading-4">@lang('review.text.give_a_review')</span>
@endif