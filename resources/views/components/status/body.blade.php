@props(['color' => null, 'svg' => null, 'undoUrl' => null])

<div class="flex justify-center items-center w-12 bg-{{ $color }}-500">
    <x-dynamic-component :component="$svg" />
</div>

<div class="-mx-3 py-2 px-4">
    <div class="mx-3">
        <span class="text-{{ $color }}-500 font-semibold">
            {{ $title }}
        </span>
        <p class="text-gray-600 text-sm">
            {{ $message }}
        </p>

        @if ($undoUrl !== null)
            <p class="text-gray-600 text-sm">
                If you made this action by mistake you can <a
                    href="{{ $undoUrl }}"
                    class="text-blue-600 hover:text-blue-900 whitespace-no-wrap w-max"
                >undo it</a>.
            </p>
        @endif
    </div>
</div>
