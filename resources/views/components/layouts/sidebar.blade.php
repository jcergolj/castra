<div
    :class="sidebarOpen ? 'block' : 'hidden'"
    @click="sidebarOpen = false"
    class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"
>
</div>

<div
    :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
    class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-gray-900 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0"
>
    <div class="flex items-center justify-center mt-8">
        <div class="flex items-center">
            <x-svg.logo/>
            <span class="text-white text-2xl mx-2 font-semibold">{{ __('Dashboard') }}</span>
        </div>
    </div>

    <nav class="mt-10">
        <a
            href="{{ route('dashboard.index') }}"
            class="flex items-center mt-4 py-2 px-6 bg-gray-700 bg-opacity-25 text-gray-100"
        >
            <x-svg.dashboard/>
            <span class="mx-3">{{ __('Dashboard') }}</span>
        </a>
    </nav>
</div>
