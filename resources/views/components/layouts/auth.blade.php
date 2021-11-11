<x-app-layout>
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200 font-roboto">
        <x-layouts.sidebar/>

        <div class="flex-1 flex flex-col overflow-hidden">
            <x-layouts.header/>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container mx-auto px-6 py-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
