<x-empty-modal no-padding="true" {{$attributes->merge()}}>
    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        {{ $slot }}
    </div>
    @isset($footer)
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            {{$footer}}
        </div>
    @endisset
</x-empty-modal>