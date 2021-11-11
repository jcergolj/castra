@props(['value'])

<label {{ $attributes->merge(['class' => 'inline-flex items-center']) }}>
    {{ $slot }}
    <span class="mx-2 text-gray-600 text-sm">{{ $value }}</span>
</label>
