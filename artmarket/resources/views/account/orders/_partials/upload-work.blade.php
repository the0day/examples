<x-panel.wrap>
    @slot('title', __('order.text.upload_work'))
    <x-form action="{{route('account.orders.upload', [$order->id])}}" enctype="multipart/form-data">
        <div>
            <x-form.label>@lang('order.text.sketch')</x-form.label>
            <input type="file" wire:model="sketches" name="sketches[]" multiple="">
        </div>
        <div class="mt-4">
            <x-form.label>@lang('order.text.final')</x-form.label>
            <input type="file" wire:model="final" name="final">
        </div>

        <div class="text-right">
            <x-button type="submit" wire:click="reply" class="px-4 py-2">
                @lang('order.button.send')
            </x-button>
        </div>
    </x-form>
</x-panel.wrap>