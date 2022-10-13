<div class="bg-white shadow sm:rounded-md pb-5 mb-5">
    @if(isset($title))
        <x-panel.head :title="$title" :description="$description ?? ''"/>
    @endif

    <div {{ $attributes->merge(['class' => 'mt-2 px-4']) }}>
        {{$slot}}
    </div>
</div>
