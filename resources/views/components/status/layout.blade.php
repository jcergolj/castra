<div @if (session('status')) x-data="{show : true}" @else x-data="{show : false}" @endif
    x-show="show" x-cloak
    class="absolute top-24 left-1/2 inline-flex max-w-sm w-full bg-white shadow-md rounded-lg overflow-hidden ml-3">

    @php
        $color = session('status')['color'] ?? null;
        $message = session('status')['message'] ?? null;
        $title = session('status')['title'] ?? null;
        $svg = session('status')['svg'] ?? null;
        $undo_url = session('status')['undo_url'] ?? null;
    @endphp

    @if ($color !== null)
        <x-status.body :color="$color" :svg="$svg" :undoUrl="$undo_url">
            <x-slot name="title">
                {{ $title }}
            </x-slot>

            <x-slot name="message">
                {{ $message }}
            </x-slot>
        </x-status.body>
    @endif
</div>
