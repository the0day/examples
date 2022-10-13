<x-empty-account-layout class="">
    <x-form :action="route('account.offers.store')" method="POST" enctype="multipart/form-data">
        @include('account.offers._form')
    </x-form>
</x-empty-account-layout>
