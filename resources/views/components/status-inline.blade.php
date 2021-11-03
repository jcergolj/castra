@if (session('status'))
    <p
        x-data="{show : true}"
        x-show="show"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
        x-init="setTimeout(() => { show = false; }, 3500);"
        x-cloak
        class="text-{{ session('status')['level'] }}-500 font-semibold"
    >
        {{ session('status')['message'] }}
    </p>
@endif
