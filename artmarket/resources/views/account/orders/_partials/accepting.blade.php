<x-panel.wrap>
    @slot('title', __('order.text.accepting'))

    <div class="grid grid-cols-2">
        <div class="text-center">
            <x-form action="{{route('account.orders.action', [$order->id])}}">
                <x-form.hidden id="action" value="decline"/>
                <x-button type="submit" class="px-4 py-2" btnColor="red" onclick="showModal('declineModal')">
                    @lang('order.button.cancel')
                </x-button>
            </x-form>


        </div>
        <div class="text-center">
            <x-form action="{{route('account.orders.action', [$order->id])}}">
                <x-form.hidden id="action" value="accept"/>
                <x-button type="submit" class="px-4 py-2">
                    @lang('order.button.accept')
                </x-button>
            </x-form>
        </div>
    </div>

</x-panel.wrap>


<x-modal id="declineModal">
    <div class="sm:flex sm:items-start">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
            <!-- Heroicon name: outline/exclamation -->
            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Deactivate account</h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500">
                    <x-form.textarea id="reason" class="w-full"></x-form.textarea>
                </p>
            </div>
        </div>
    </div>
    @slot('footer')
        <x-button class="sm:ml-3" btnColor="red">@lang('order.button.confirm')</x-button>
        <x-button class="sm:ml-3">@lang('app.buttons.close')</x-button>
    @endslot
</x-modal>