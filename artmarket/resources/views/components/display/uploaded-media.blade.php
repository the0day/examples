@if ($items && $items->count() > 0)
    <div class="grid grid-cols-5 gap-4 h-32" x-data>
        @foreach($items as $media)
            <div class="relative flex flex-col items-center overflow-hidden text-center bg-gray-100 border rounded select-none"
                 wire:key="media-{{md5($media->uuid)}}">
                <a @click="$dispatch('lightbox', {  imgModalSrc: '{{$media->getFullUrl()}}' })"
                   class="cursor-pointer">

                    <img src="{!! $media->getFullUrl() !!}"
                         class="object-cover mx-auto object-center h-32 w-32 rounded"/>
                </a>
            </div>
        @endforeach
    </div>
@endif
