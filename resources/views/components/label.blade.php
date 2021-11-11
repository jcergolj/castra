@props(['value' => null])

<label>
    @if ($value !== null)
        <span class="text-gray-700 text-sm">{{ $value }}</span>
    @endif

    {{ $slot }}
</label>
