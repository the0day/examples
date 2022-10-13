@extends('account.frame')

@section('content')
    <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
        <section aria-labelledby="{{$label ?? "" }}">
            <div class="shadow sm:rounded-md bg-white"> <!--  sm:overflow-hidden -->
                <div class="{{ $attributes->get('class', '') }} divide-y">
                    <div class="{{isset($buttons) ? 'sm:flex sm:items-center sm:justify-between' : ''}} p-4">
                        <div>
                            <h2 class="text-lg leading-6 font-medium text-gray-900">{{$title ?? ''}}</h2>
                            <p class="mt-1 text-sm text-gray-500">{{$description ?? ''}}</p>
                            <x-errors/>
                        </div>
                        {{$buttons ?? null}}
                    </div>
                    <div>{{ $slot }}</div>
                </div>
            </div>
        </section>
    </div>
@endsection
