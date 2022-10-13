<?php
/**
 * @var Message $message
 */

use App\Models\Message;

?>
<div class="relative flex items-start space-x-3">
    <div class="relative">
        <img class="h-10 w-10 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white"
             src="https://images.unsplash.com/photo-1520785643438-5bf77931f493?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80"
             alt="">
    </div>
    <div class="min-w-0 flex-1">
        <div>
            <div class="text-sm">
                <a href="#" class="font-medium text-gray-900">{{$message['user']['name']}}</a>
            </div>
            <p class="mt-0.5 text-sm text-gray-500"> {{$message['published_at']}}</p>
        </div>
        <div class="mt-2 text-sm text-gray-700" wire:key="message-{{$message['id']}}">
            <p>{{$message['body']}}</p>
            @foreach ($message['images'] as $image)
                <a @click="$dispatch('lightbox', {  imgModalSrc: '{{$image['original_url']}}' })"
                   class="cursor-pointer">
                    <img src="{{$image['original_url']}}"
                         class="flex-none w-24 h-24 bg-gray-100 rounded-md object-center object-cover">
                </a>
            @endforeach

        </div>
    </div>
</div>
