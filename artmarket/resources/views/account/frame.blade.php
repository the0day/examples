@php
    $links = [
                'account.offers.list' => [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />',
                    'title' => 'account.offers.title',
                    'description' => 'account.offers.description'
                ],
                'account.orders' => [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    'title' => 'account.navigation.orders',
                    'description' => 'account.orders.description'
                ],
                'account.purchases' => [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>',
                    'title' => 'account.navigation.purchases',
                    'description' => 'account.purchases.description'
                ],
                'account.payments' => [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>',
                    'title' => 'account.navigation.payments',
                    'description' => 'account.payments.description'
                ],
                'account.reviews' => [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />',
                    'title' => 'account.navigation.reviews',
                    'description' => ''
                ],
                'account.personal' => [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    'title' => 'account.navigation.personal',
                    'description' => 'account.personal.description'
                ],
            ];
@endphp
<x-app-layout main-class="max-w-7xl mx-auto pb-10 lg:py-12 lg:px-8">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('account.title')
        </h2>
    </x-slot>

    <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
        <aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
            <i class="fa-thin fa-alarm-clock"></i>
            <nav class="">
                @foreach($links as $route => $link)

                    <a href="{{route($route)}}"
                       class="{{Route::is($route) ? "text-teal-500" : "text-gray-500 hover:text-gray-600"}} group px-3 py-2 flex items-center"
                       aria-current="page">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="flex-shrink-0 -ml-1 mr-4 h-6 w-6"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            {!! $link['icon'] !!}
                        </svg>

                        <span class="truncate">@lang($link['title'])</span>
                    </a>
                @endforeach
            </nav>
        </aside>

        @yield('content')
    </div>
</x-app-layout>
