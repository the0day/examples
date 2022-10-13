<?php
/**
 * @var Offer $offer
 * @var Order $order
 */

use App\Models\Offer;
use App\Models\Order;

$offer = $order->offer;
$list = [
    __('order.text.deadline')        => $order->deadline_at,
    __('order.text.order_placed_at') => $order->created_at,
];
$options = $order->getUpgradesReadable();

?>
<x-app-layout main-class="max-w-7xl mx-auto pb-10 lg:py-12 lg:px-8">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if(isset($errors) && $errors->any())
        <section aria-labelledby="products-heading" class="mt-6">
            <div class="space-y-8">
                <div class="bg-white border-t border-b border-gray-200 sm:border sm:rounded-lg">
                    <div class="border-b border-gray-200 pb-3 px-4">
                        <x-errors/>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <div class="min-h-full flex mt-8">
        <div class="mx-auto xl:grid xl:grid-cols-3">
            <div class="xl:col-span-2 xl:pr-8">
                @can('upload', $order)
                    @include('account.orders._partials.upload-work')
                @elsecan('accept', $order)
                    @include('account.orders._partials.accepting')
                @elsecan('approve', $order)
                    @include('account.orders._partials.approve')
                @endcan

                @include('account.orders._partials.uploaded')

                <livewire:order-chat :order="$order"/>
            </div>
            <div class="xl:col-span-1">
                @include('account.orders._partials.info')

                <x-panel.wrap>
                    @slot('title', __('order.field.'.$order->counterpartyKey(Auth::user())))
                    <x-user.mini :user="$order->counterparty(Auth::user())"/>
                </x-panel.wrap>
            </div>
        </div>
    </div>
</x-app-layout>
