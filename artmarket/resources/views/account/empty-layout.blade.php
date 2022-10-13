@extends('account.frame')

@section('content')
    <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
        <section aria-labelledby="{{$label ?? "" }}">
            {{ $slot }}
        </section>
    </div>
@endsection
