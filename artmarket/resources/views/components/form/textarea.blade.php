<textarea
        name="{{ $id }}" id="{{ $id }}"
        rows="{{ $rows ?? 3 }}"
    {{ $attributes->merge(['class' => 'shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md']) }}>{{$value ?? ""}}</textarea>
