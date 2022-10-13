<?php
if (!isset($icon)) {
    $icon = '<svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
}

if (!isset($title)) {
    $title = __('app.confirm_action');
}

?>
<x-modal {{$attributes->merge()}}>
    <div class="sm:flex sm:items-start">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
            {!! $icon !!}
        </div>
        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $title }}</h3>
            <div class="mt-2">
                {{ $slot }}
            </div>
        </div>
    </div>
    @slot('footer')
        <x-button class="sm:ml-3" btnColor="red"
                  x-on:click="document.forms.namedItem('{{$formId}}').submit();">@lang('order.button.confirm')</x-button>
        <x-button type="button" class="sm:ml-3" x-on:click="open = false">@lang('app.buttons.close')</x-button>
    @endslot
</x-modal>