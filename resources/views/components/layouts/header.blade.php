<header class="flex justify-between items-center py-4 px-6 bg-white border-b-4 border-blue-600">
    <div class="flex items-center">
        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
            <x-svg.hamburger/>
        </button>

        <div class="relative mx-4 lg:mx-0">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                <x-svg.search/>
            </span>
            <input
                type="text"
                class="form-input w-32 sm:w-64 rounded-md pl-10 pr-4 focus:border-blue-600"
                placeholder="{{__('Search')}}"
            >
        </div>
    </div>

    <div class="flex items-center">
        <div x-data="{ dropdownOpen: false }"  class="relative">
            <button
                @click="dropdownOpen = ! dropdownOpen"
                class="relative block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none"
            >
                <img
                    src="https://images.unsplash.com/photo-1528892952291-009c663ce843?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=296&q=80"
                    class="h-full w-full object-cover"
                    alt="{{ __('Your avatar') }}"
                >
            </button>

            <div
                x-show="dropdownOpen"
                @click="dropdownOpen = false"
                class="fixed inset-0 h-full w-full z-10"
            >
            </div>

            <div
                x-show="dropdownOpen"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10"
            >
                <a
                    href="{{ route('account.profile') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-600 hover:text-white"
                >
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}" class="max-width">
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-600 hover:text-white"
                    >
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
