<x-layouts.auth>
    <h3 class="text-gray-700 text-3xl font-semibold">Your profile</h3>
    <div class="mt-8">
        <div class="mt-4">
            <div class="p-6 bg-white rounded-md shadow-md">
                <h2 class="text-lg text-gray-700 font-semibold capitalize">Password</h2>
                <turbo-frame id="frame_update_password" @turboReload>
                    <form
                        id="update_password"
                        action="{{ route('accounts.passwords.update') }}"
                        method="POST"
                        class="grid grid-cols-1 gap-6 mt-4"
                    >
                        @csrf
                        @method('PATCH')

                        <div class="mt-4 max-w-md">
                            <x-label for="current_password" class="text-gray-700">
                                {{ trans_validation_attribute('current_password') }}
                                <x-input
                                        id="current_password"
                                        class="mt-2"
                                        type="password"
                                        name="current_password"
                                        required
                                />
                                <x-error field="current_password"/>
                            </x-label>
                        </div>

                        <div class="mt-4 max-w-md">
                            <x-label for="new_password" class="text-gray-700">
                                {{ trans_validation_attribute('new_password') }}
                                <x-input
                                        id="password"
                                        class="mt-2"
                                        type="password"
                                        name="new_password"
                                        required
                                />
                                <x-error field="new_password"/>
                            </x-label>
                        </div>

                        <div class="mt-4 max-w-md">
                            <x-label for="new_password_confirmation" class="text-gray-700">
                                {{ trans_validation_attribute('new_password_confirmation') }}
                                <x-input
                                        id="new_password_confirmation"
                                        class="mt-2"
                                        type="password"
                                        name="new_password_confirmation"
                                        required
                                />
                                <x-error field="new_password_confirmation"/>
                            </x-label>
                        </div>

                        <div class="flex justify-between items-center mt-6 max-w-md">
                            <a
                                @turboReload
                                class="py-2 px-4 text-sm rounded-md bg-white border-4 border-opacity-20 hover:bg-gray-300 focus:outline-none"
                                href="{{ route('accounts.profile') }}"
                            >
                                Cancel
                            </a>
                            <x-button>Save</x-button>
                        </div>
                    </form>
                </turbo-frame>
            </div>
        </div>
    </div>
</x-layouts.auth>
