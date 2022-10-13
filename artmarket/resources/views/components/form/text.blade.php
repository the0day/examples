@php
    $class[] = $attributes->get('class');
    $class[] = 'focus:ring-blue-500 focus:border-blue-300 block sm:text-sm border-gray-300';
    $class[] = $errors->has($id) ? 'border-red-500' : '';
    $class[] = isset($prepend) ? 'flex-1 rounded-none rounded-r-md' : 'rounded-md mt-1';
    $class[] = $size ?? 'w-full';
    $class = implode(" ", $class);

    $prependClass = $errors->has($id) ? 'border-red-500 bg-gray-50 text-red-500' : 'border-gray-300 bg-gray-50 text-gray-500';
@endphp

@if(isset($prepend))
    <div class="mt-1 flex justify-start rounded-md shadow-sm">
        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 text-sm {{$prependClass}}">
            {{$prepend}}
        </span>
        @endif

        <input type="{{ $type ?? "text" }}"
               value="{{$value ?? ""}}" {{ $attributes->merge(['class' => $class])->except('prepend')}}/>

        @isset($prepend) </div>
@endisset
