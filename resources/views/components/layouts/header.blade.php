<header class="flex justify-between items-center py-4 px-6 bg-white border-b-4 border-blue-600">
    <div class="flex items-center">
        <button
            @click="sidebarOpen = true"
            class="text-gray-500 focus:outline-none lg:hidden"
        >
            <x-svg.hamburger />
        </button>
    </div>

    <div class="flex items-center">
        <div
            x-data="{ dropdownOpen: false }"
            x-cloak
            class="relative"
        >
            <button
                @click="dropdownOpen = ! dropdownOpen"
                class="relative block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none"
            >
                <turbo-frame id="header_profile_image">
                    @include('layouts._profile-image')
                </turbo-frame>
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
                    href="{{ route('accounts.profile') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-600 hover:text-white"
                >
                    Profile
                </a>
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="block px-4 py-2 text-sm text-left text-gray-700 hover:bg-blue-600 hover:text-white w-full"
                    >
                        Logout
                    </button>


                </form>
            </div>
        </div>
    </div>
</header>
