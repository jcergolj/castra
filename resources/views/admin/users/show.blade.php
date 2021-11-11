<x-layouts.auth>
    <div class="mt-8">
        <div class="mt-4">
            <div class="p-6 bg-white rounded-md shadow-md">
                <div class="flex justify-between">
                    <h3 class="text-gray-700 text-3xl font-medium">{{ $user->email }}</h3>
                    <a
                        href="{{ url()->previous() }}"
                        class="text-blue-600 hover:text-blue-900 whitespace-no-wrap w-max"
                        title="back to users"
                    >
                        <x-svg.back />
                    </a>
                </div>

                <turbo-frame id="frame_update_profile_image">
                    <div class="flex flex-col items-center justify-center mt-8">
                        <a
                            href="{{ route('admin.user-images.edit', $user) }}"
                            class="text-blue-600 hover:text-blue-900 whitespace-no-wrap"
                            title="edit user's image"
                        >
                            <div class="rounded-full overflow-hidden h-40 w-40">
                                <img
                                    src="{{ $user->profileImageFile }}"
                                    class="h-full w-full"
                                >
                            </div>
                        </a>

                        <div class="text-center">
                            <x-status.inline messageBag="update-image" />
                        </div>
                    </div>
                </turbo-frame>

                <turbo-frame id="frame_update_email">
                    <div class="flex justify-center mt-8">
                        <div class="text-gray-700">{{ $user->email }}</div>
                        <a
                            href="{{ route('admin.user-emails.edit', $user) }}"
                            class="text-blue-600 hover:text-blue-900 whitespace-no-wrap ml-3"
                        >
                            <x-svg.edit />
                        </a>

                        <div class="grid grid-cols-1 mt-4">
                            <x-status.inline messageBag="update-email" />
                        </div>
                    </div>
            </div>
            </turbo-frame>
        </div>
    </div>
    </div>
</x-layouts.auth>
