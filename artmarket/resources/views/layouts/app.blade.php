<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{--Fonts--}}
    {{--<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">--}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">

    {{--Styles--}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v6.1.1/css/all.css"/>


    {{-- Scripts --}}
    <script src="{{ asset('js/app.js') }}" defer></script>

    @livewireStyles
</head>
<body class="font-sans antialiased text-gray-600 min-h-full">
<div class="">
    @include('layouts.header')
    {{--Page Heading--}}
    {{--<header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </header>--}}

    {{--Page Content--}}
    <main class="{{ $attributes->get('main-class') ?? "max-w-7xl mx-auto pb-10" }}">
        {{ $slot }}
    </main>
</div>
<x-notification/>
<x-lightbox/>
@guest
    <x-modal.auth/>
@endguest
@livewireScripts
<script src="//unpkg.com/flowbite@1.4.3/dist/datepicker.js"></script>
</body>
</html>
