<?php
/** @var Offer $offer */

use App\Models\Offer;

?>
<x-empty-account-layout class="">
    <x-form :action="route('account.offers.store')" method="POST" enctype="multipart/form-data">
        @bind($offer)
        <x-form-input name="id" id="id" type="hidden"/>
        @include('account.offers._form', ['offer' => $offer])
        @endbind
    </x-form>
</x-empty-account-layout>


{{$offer->getMedia()}}
