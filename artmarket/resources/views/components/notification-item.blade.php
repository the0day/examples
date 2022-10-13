<a href="{{$link}}" class="transparent" role="button">
    <div class="flex border-b">
        <div class="w-1/5">
            <img class="" alt="" src="{{$image}}">
        </div>
        <div class="w-4/5 p-2">
            <div class="flex justify-between text-sm font-medium text-gray-900">
                <div>{{$title}}</div>
                <div class="text-gray-400 text-xs">{{$date}}</div>
            </div>
            <p class="mt-1 text-sm text-gray-500">
                {{$body}}
            </p>
        </div>

    </div>
</a>