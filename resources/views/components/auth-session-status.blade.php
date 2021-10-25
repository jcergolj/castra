@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-green-500 font-semibold']) }}>
        {{ $status }}
    </div>
@endif
