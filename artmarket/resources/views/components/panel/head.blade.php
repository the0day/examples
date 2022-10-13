<div class="border-b border-gray-200 sm:flex sm:items-center sm:justify-between px-4 py-3">
    <div>
        <h2 class="text-lg leading-6 font-medium text-gray-900">{{$title}}</h2>
        @isset($description)
            <p class="mt-1 text-sm text-gray-500">{{$description}}</p>
        @endisset
    </div>
</div>
