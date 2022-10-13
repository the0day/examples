<x-panel.wrap>
    @slot('title', __('order.text.approving'))

    <div class="grid grid-cols-2">
        <div class="text-center">

            <x-button type="button" class="px-4 py-2" btnColor="red" onclick="showModal('declineModal')">
                @lang('order.button.cancel')
            </x-button>


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


<x-modal.confirm id="declineModal" formId="form-approve">
    <x-form action="{{route('account.orders.action', [$order->id])}}" id="form-approve">
        <x-form.hidden id="action" value="decline"/>
        <x-form.textarea id="comment" name="comment" class="w-full"></x-form.textarea>
    </x-form>
</x-modal.confirm>