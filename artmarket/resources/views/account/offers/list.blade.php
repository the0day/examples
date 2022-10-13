<x-account-layout :title="__('account.offers.title')" :description="__('account.offers.description')">
    @slot('buttons')
        <a href="{{route("account.offers.create", ['offerType' => 'art'])}}"
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            @lang('account.offers.create')
        </a>
    @endslot

    <livewire:offer-datatable/>
</x-account-layout>
