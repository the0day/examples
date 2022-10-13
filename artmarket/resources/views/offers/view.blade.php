@php
    use App\Models\Offer;
    use App\Enums\MediaCollectionType;

    /** @var Offer $offer */

    /** @TODO: Добавить Member since */
@endphp

<x-app-layout main-class="max-w-7xl mx-auto pb-10 lg:py-12 lg:px-8">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <section class="py-10">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap">
                <x-offer.gallery :items="$offer->getPreviewMedia()"/>
                <div class="w-full md:w-2/5 px-4">
                    <div class="lg:pl-5">
                        <div class="mb-10 pb-10 border-b">
                            <div class="flex flex-wrap md:flex-nowrap items-center pb-2 whitespace-nowrap">
                                <div class="min-w-0 flex items-center">
                                    <h2 class="max-w-xl text-xl md:text-2xl font-bold font-heading">{{$offer->title}}</h2>
                                </div>
                            </div>
                            <div class="flex items-baseline">
                                <x-reviews-line :max="5" :current="3" :rating="4.32" :total="143" class="flex-1"/>
                                <div class="flex-none">
                                    <p class="text-3xl text-gray-900">{{$offer->getPrice()}}</p>
                                </div>
                            </div>
                        </div>

                        <x-form action="{{route('offer.checkout', [$offer->user->name, $offer->alias])}}"
                                id="form-checkout">
                            @auth
                                <div class="mb-4">
                                    @foreach($offer->options as $option)
                                        @includeWhen($option->price || $option->field_values, 'offers.option')
                                    @endforeach
                                </div>
                            @endauth

                            <div class="mb-12">
                                @if(Auth::check())
                                    <div class="text-right flex mb-6 justify-end">
                                        Итоговая стоимость:
                                        <div id="price-total" class="font-bold">{{$offer->getPrice()}}</div>
                                    </div>
                                    <x-button class="w-full py-3 px-6">
                                        <i class="fas fa-cart-plus mr-2"></i>
                                        @lang('offer.button.order')
                                    </x-button>
                                @endif

                                @unless(Auth::check())
                                    <x-button type="button"
                                              class="w-full py-3 px-6"
                                              onclick="showModal('auth')">
                                        <i class="fas fa-cart-plus mr-2"></i> @lang('offer.button.order')
                                    </x-button>
                                @endunless
                            </div>
                        </x-form>

                        <div class="mb-10 pb-10 border-b">
                            <p class="max-w-md text-gray-500"><?=$offer->description?></p>
                        </div>
                        <div>
                            <x-user.mini :user="$offer->user"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:w-3/5 bg-cover mb-8 md:mb-0 lg:border-r lg:border-gray-200 pt-8">
                <ul class="flex flex-wrap mb-8 border-b-2">
                    <li class="w-1/2 md:w-auto">
                        <a class="inline-block py-3 px-5 bg-white text-gray-500 font-medium shadow-md" href="#">
                            @lang('offer.text.description')
                        </a>
                    </li>
                    <li class="w-1/2 md:w-auto">
                        <a class="inline-block py-3 px-5 text-gray-500" href="#">
                            @lang('offer.text.reviews')
                        </a>
                    </li>
                </ul>
                <p class="text-gray-600">
                    {{$offer->description}}
                </p>
            </div>
        </div>
    </section>
</x-app-layout>
