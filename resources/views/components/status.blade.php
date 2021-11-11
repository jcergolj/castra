<div
    @if (session('status')) x-data="{show : true}" @else x-data="{show : false}" @endif
    x-data="{show : true}"
    x-show="show"
    x-init="setTimeout(() => { show = false; }, 3500);"
    x-cloak
    @status.window="
        show = true;
        console.log('listened');
        $refs.message.innerHTML = '{{ session('status')['message'] ?? '' }}';
        setTimeout(() => { show = false; }, 3500);
    "
    class="inline-flex max-w-sm w-full bg-white shadow-md rounded-lg overflow-hidden ml-3"
>
    <div class="flex justify-center items-center w-12 bg-green-500">
        <x-svg.success></x-svg.success>
    </div>

    <div class="-mx-3 py-2 px-4">
        <div class="mx-3">
            <span class="text-green-500 font-semibold">Success</span>
            <p x-ref="message" class="text-gray-600 text-sm"></p>
        </div>
    </div>
</div>
