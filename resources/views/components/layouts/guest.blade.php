<x-app-layout>
    <div class="flex justify-center items-center h-screen bg-gray-200 px-6">
        <div class="p-6 max-w-sm w-full bg-white shadow-md rounded-md">
            <div class="flex justify-center items-center">
                <x-svg.logo/>
                <span class="text-gray-700 font-semibold text-2xl">{{__('Admin Dashboard')}}</span>
            </div>

            {{ $slot }}

        </div>
    </div>
</x-app-layout>
