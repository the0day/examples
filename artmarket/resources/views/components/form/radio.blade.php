<div class="flex items-center">
    @foreach ($items as $key => $label)
        <input id="{{$key}}" name="{{$key}}" type="radio"
               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
        <label for="{{$key}}" class="mx-3 block text-sm font-medium text-gray-700">
            {{$label}}
        </label>
    @endforeach
</div>
