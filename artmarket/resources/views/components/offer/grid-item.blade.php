<?php
/**
 * @var Offer $item
 */

use App\Enums\MediaConversion;use App\Models\Offer;

?>
<div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
    <div class="group relative">
        <div class="bg-gray-200 rounded-md overflow-hidden group-hover:opacity-75 w-full relative"
             style="padding-bottom:100%;">
            <div class="local bg-center w-full h-full absolute bg-cover bg-no-repeat"
                 style="background-image: url('<?=$item->getFirstPreview()->getUrl(MediaConversion::thumb())?>')"
                 alt="{{$item->title}}"></div>
        </div>
        <div class="mt-4 flex justify-between">
            <div>
                <h3 class="text-sm text-gray-700">
                    <a href="{{$item->getUrl()}}">
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{$item->title}}
                    </a>
                </h3>
                <p class="mt-1 text-sm text-gray-500">Black</p>
            </div>
            <p class="text-sm font-medium text-gray-900">{{$item->getPrice()}}</p>
        </div>
    </div>
</div>
