@if ($items && $items->count() > 0)
    <div class="grid grid-cols-5 gap-4 h-32" wire:sortable="order">
        @foreach($items as $media)
            <div wire:sortable.item="{{ $media->id }}" wire:key="media-{{ $media->id }}"
                 class="relative flex flex-col items-center overflow-hidden text-center bg-gray-100 border rounded cursor-move select-none">
                <button class="absolute top-0 right-0 z-50 p-1 bg-white rounded-bl focus:outline-none"
                        type="button" wire:click="remove('{{$media->uuid}}')">
                    <svg class="w-4 h-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>

                <img src="{!! $media->getFullUrl() !!}" class="object-cover mx-auto object-center h-32 w-32 rounded"/>
            </div>
        @endforeach
    </div>
@endif
