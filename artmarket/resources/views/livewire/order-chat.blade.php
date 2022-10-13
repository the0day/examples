<?php
/**
 * @var Order $order
 */

use App\Models\Order;
?>
<section aria-labelledby="chat-title">
    <x-panel.wrap>
        @slot('title', __('order.text.chat'))
        @slot('description')
            @if ($receiverUser['online'])
                <i class="fa-solid fa-circle text-green-600 fa-sm"></i>
                @lang('order.text.online', ['login' => $receiverUser['name']])
            @else
                <i class="fa-solid fa-circle text-red-600 fa-sm"></i>
                @lang('order.text.was_online', [
                    'login' => $receiverUser['name'],
                    'time' => $receiverUser['last_seen_at']
                ])
            @endif
        @endslot

        <div class="flow-root">
            <ul role="list" class="-mb-8" id="chat-container" data-chat-id="{{$order->id}}"
                x-init="Echo.private('chat.<?=$order->id?>').listen('new-message', (e) => {@this.call('incomingMessage', e)})"
                x-data="{ isUploading: false, progress: 0 }"
                x-on:livewire-upload-start="isUploading = true"
                x-on:livewire-upload-finish="isUploading = false"
                x-on:livewire-upload-error="isUploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">

                @if(count($messages))
                    @if ($isMore)
                        <div class="text-center">
                            <button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900"
                                    type="button"
                                    wire:click="loadMore">Load more
                            </button>
                        </div>
                    @endif

                    @foreach($messages as $i => $message)
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200"
                                      aria-hidden="true"></span>
                                @include('livewire.chat.message')
                            </div>
                        </li>
                    @endforeach
                @else
                    <div class="text-gray-500 text-center mb-20 mt-20 flex justify-center items-center">
                        <i class="fa-regular fa-comments fa-2x text-gray-300"></i>
                        <span class="ml-3">@lang('order.text.no_messages')</span>
                    </div>
                @endif
            </ul>
        </div>
        <div class="mt-6">
            <div class="flex space-x-3">
                <div class="flex-shrink-0">
                    <div class="relative">
                        <img class="h-10 w-10 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white"
                             src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80"
                             alt="">
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <div>
                        <label for="comment" class="sr-only">Comment</label>
                        <textarea id="comment" name="comment" rows="3" wire:model="text"
                                  class="shadow-sm block w-full focus:ring-gray-900 focus:border-gray-900 sm:text-sm border border-gray-300 rounded-md"
                                  placeholder="Leave a comment"></textarea>

                        <div class="mt-6 flex items-center justify-between space-x-4">
                            <input type="file" wire:model="images" multiple>
                            <x-button type="submit" wire:click="reply" class="px-4 py-2">
                                @lang('order.button.send')
                            </x-button>
                        </div>

                        <div class="pt-4">
                            @error('images.*') <span class="error">{{ $message }}</span> @enderror
                            @if ($images)
                                @foreach($images as $image)
                                    <img src="{{ $image->temporaryUrl() }}"
                                         alt="Attached image"
                                         class="flex-none w-24 h-24 bg-gray-100 rounded-md object-center object-cover"/>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-panel.wrap>

</section>