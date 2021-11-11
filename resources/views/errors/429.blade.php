@php
    $title = '429 - Too many requests';
    $message = $exception->getMessage() ?: 'Too many requests.';
@endphp

<x-layouts.guest>
    <div class="mt-4">
        <h3 class="text-gray-700 text-3xl font-medium">{{ $title }}</h3>
        <h4 class="text-gray-600 mt-4">{{ $message }}</h4>
    </div>
</x-layouts.guest>
