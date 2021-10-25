@props(['value'])

<label {{ $attributes->merge(['class' => 'block']) }}>
    <span class="text-gray-700 text-sm">{{ $value }}</span>
    {{ $slot }}
</label>
