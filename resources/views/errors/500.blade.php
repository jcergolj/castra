@php
    $title = '500 - Server error';
    $message = $exception->getMessage() ?: 'Something went wrong on our end.';
@endphp

<x-layouts.guest>
    <div class="mt-4">
        <h3 class="text-gray-700 text-3xl font-medium">{{ $title }}</h3>
        <h4 class="text-gray-600 mt-4">{{ $message }}</h4>
    </div>
</x-layouts.guest>
