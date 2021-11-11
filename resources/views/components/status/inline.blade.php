@props(['messageBag' => null])

@if (session('status') && session('status')['message_bag'] === $messageBag)
    <p
        x-data="{show : true}"
        x-show="show"
        x-cloak
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
        x-init="setTimeout(() => { show = false; }, 3500);"
        class="text-{{ session('status')['color'] }}-500 font-semibold"
    >
        {{ session('status')['message'] }}
    </p>

    <?php session()->forget('status'); ?>
@endif
