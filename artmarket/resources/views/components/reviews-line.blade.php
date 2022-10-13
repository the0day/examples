<div class="flex items-baseline relative bottom-1 {{$class ?? ""}}  text-sm">
    <x-rating :max="$max" :current="$current"/>
    @isset($rating)
        <div class="text-yellow-400">{{$rating}}</div>
    @endisset
    @isset($total)
        <div class="text-gray-600 ml-1">({{$total}})</div>
    @endisset
</div>
