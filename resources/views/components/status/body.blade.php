@props(['color' => null])

<div
    x-data="{ show: true }" x-show="show" x-cloak
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90" x-init="setTimeout(() => { show = false; }, 3500);"
    class="-mx-3 py-2 px-4"
>
    <div class="mx-3">
        <span class="text-{{ $color }}-500 font-semibold">
            {{ $title }}
        </span>
        <p class="text-gray-600 text-sm">
            {{ $message }}
        </p>
    </div>
</div>
