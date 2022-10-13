<?php
/**
 * @var Offer $offer
 */

use App\Models\Offer;

?>
<x-app-layout main-class="max-w-7xl mx-auto pb-10 lg:py-12 lg:px-8">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <x-form action="{{route('offer.checkout.payment', [$offer->user->name, $offer->alias])}}"
            enctype="multipart/form-data">
        <x-errors/>
        <div class="px-4 space-y-2 sm:px-0 sm:flex sm:items-baseline sm:justify-between sm:space-y-0">
            <div class="flex sm:items-baseline sm:space-x-4">
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">
                    @lang('order.text.id', [$order->id])</h1>
            </div>
            <p class="text-sm text-gray-600">@lang('order.text.order_placed_at')
                <time datetime="{{$order->created_at->format('Y-m-d')}}"
                      class="font-medium text-gray-900">{{$order->created_at->format('d/m/Y H:i')}}</time>
            </p>
        </div>

        <!-- Products -->
        <section aria-labelledby="products-heading" class="mt-6">
            <h2 id="products-heading" class="sr-only">Products purchased</h2>

            <div class="space-y-8">
                <div class="bg-white border-t border-b border-gray-200 shadow-sm sm:border sm:rounded-lg">
                    <div class="py-6 px-4 sm:px-6 lg:grid lg:grid-cols-12 lg:gap-x-8 lg:p-8">
                        <div class="sm:flex lg:col-span-7">
                            <div class="flex-shrink-0 w-full aspect-w-1 aspect-h-1 rounded-lg overflow-hidden sm:aspect-none sm:w-40 sm:h-40">
                                @if ($offer->getFirstPreview())
                                    <img src="{{$offer->getFirstPreview()->getUrl(App\Enums\MediaConversion::thumb())}}"
                                         alt="{{$offer->title}}"
                                         class="w-full h-full object-center object-cover sm:w-full sm:h-full">
                                @endif
                            </div>

                            <div class="mt-6 w-full sm:mt-0 sm:ml-6">
                                <h3 class="text-base font-medium text-gray-900">
                                    <a href="#">{{$offer->title}}</a>
                                </h3>
                                <div class="mt-3 text-sm text-gray-500">
                                    <div class="mb-3">
                                        <x-form.form-date
                                                name="deadline" :label="__('order.text.deadline')"
                                                data-min-date="{{\Carbon\Carbon::now()->addDays(2)->format('d/m/Y')}}"
                                                value="{{$order->deadline_at ? $order->deadline_at->format('d/m/Y') : \Carbon\Carbon::now()->addDays(4)->format('d/m/Y')}}"/>
                                    </div>
                                    <x-form-textarea name="notes" class="w-full h-24"
                                                     :default="$order->note_to_seller"/>
                                </div>
                            </div>
                        </div>

                        <div class="lg:mt-0 lg:col-span-5">
                            <h3 class="text-base font-medium text-gray-900 pb-1">
                                @lang('order.text.upload_samples')
                            </h3>
                            <livewire:order-sample-upload :order-id="$order->id"/>
                            <livewire:uploaded-media
                                    :items="$order->getMedia(App\Enums\MediaCollectionType::orderSample())"/>
                            {{--<dl class="grid grid-cols-2 gap-x-6 text-sm">
                                <div>
                                    <dt class="font-medium text-gray-900">Delivery address</dt>
                                    <dd class="mt-3 text-gray-500">
                                        <span class="block">Floyd Miles</span>
                                        <span class="block">7363 Cynthia Pass</span>
                                        <span class="block">Toronto, ON N3Y 4H8</span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-900">Shipping updates</dt>
                                    <dd class="mt-3 text-gray-500 space-y-3">
                                        <p>f•••@example.com</p>
                                        <p>1•••••••••40</p>
                                        <button type="button" class="font-medium text-indigo-600 hover:text-indigo-500">
                                            Edit
                                        </button>
                                    </dd>
                                </div>
                            </dl>--}}
                        </div>
                    </div>

                    <div class="border-t border-gray-200 py-6 px-4 sm:px-6 lg:p-8">
                        <x-progress-bar
                                active="1"
                                :items="['order.steps.new_order', 'order.steps.payment', 'order.steps.approve', 'order.steps.work', 'order.steps.delivery']"/>
                    </div>
                </div>
            </div>
        </section>

        <!-- Billing -->
        <section aria-labelledby="summary-heading" class="mt-16">
            <h2 id="summary-heading" class="sr-only">Billing Summary</h2>

            <div class="bg-gray-100 py-6 px-4 sm:px-6 sm:rounded-lg lg:px-8 lg:py-8 lg:grid lg:grid-cols-12 lg:gap-x-8">
                <dl class="grid grid-cols-2 gap-6 text-sm sm:grid-cols-2 md:gap-x-8 lg:col-span-7">
                    <div>
                        Ваш баланс: {{Auth::user()->credit}}

                        @foreach($paymentGateways as $paymentGateway)
                            <x-form-radio name="payment_method" value="{{$paymentGateway->name}}"
                                          :label="$paymentGateway->title"/>
                        @endforeach
                    </div>
                </dl>

                <dl class="mt-8 divide-y divide-gray-200 text-sm lg:mt-0 lg:col-span-5">
                    <div class="pb-2 flex items-center justify-between">
                        <dt class="text-gray-600">@lang('order.text.job_cost')</dt>
                        <dd class="font-medium text-gray-900">{{$order->job_cost->formatByIntl(true)}}</dd>
                    </div>
                    @if($order->upgrade_cost->isPositive())
                        <div class="py-2 flex items-center justify-between">
                            <dt class="text-gray-600">@lang('order.text.upgrade_cost')</dt>
                            <dd class="font-medium text-gray-900">{{$order->upgrade_cost->formatByIntl(true)}}</dd>
                        </div>
                    @endif

                    @if($order->discount->isPositive())
                        <div class="py-2 flex items-center justify-between">
                            <dt class="text-gray-600">@lang('order.text.discount')</dt>
                            <dd class="font-medium text-gray-900">-{{$order->discount->formatByIntl(true)}}</dd>
                        </div>
                    @endif

                    @if($order->service_fee->isPositive())
                        <div class="py-2 flex items-center justify-between">
                            <dt class="text-gray-600">@lang('order.text.service_fee')</dt>
                            <dd class="font-medium text-gray-900">{{$order->service_fee->formatByIntl(true)}}</dd>
                        </div>
                    @endif
                    <div class="pt-3 flex items-center justify-between">
                        <dt class="font-medium text-gray-900">@lang('order.text.total_cost')</dt>
                        <dd class="font-medium text-indigo-600">{{$order->total_cost->formatByIntl(true)}}</dd>
                    </div>

                    <div class="pt-5 text-right">
                        <x-button class="py-4 px-8 justify-center ">
                            {{__('order.button.payment')}}
                        </x-button>
                    </div>
                </dl>
            </div>
        </section>
    </x-form>
</x-app-layout>
