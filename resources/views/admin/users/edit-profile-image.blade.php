<x-layouts.auth>
    <div class="mt-8">
        <div class="mt-4">
            <div class="p-6 bg-white rounded-md shadow-md">
                <div class="flex justify-between">
                        <h3 class="text-gray-700 text-3xl font-medium">{{ $user->email }}</h3>
                        <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-900 whitespace-no-wrap w-max" title="back to users">
                            <x-svg.back />
                        </a>
                </div>

                <turbo-frame id="frame_update_profile_image" @turboReload>
                    <form
                        id="update_profile_image"
                        action="{{ route('admin.user-images.update', $user) }}"
                        method="POST"
                        class="flex flex-col items-center justify-center mt-8"
                        enctype="multipart/form-data"
                        @hasTurboFrameHeader data-turbo="false" @endHasTurboFrameHeader
                    >
                        @csrf
                        @method('PATCH')

                        <div class="mt-4 max-w-md">
                            <x-label class="text-gray-700">
                                {{ trans_validation_attribute('user_image') }}
                                <div x-data="{profileImage: false}" x-cloak class="mt-4">
                                    <div class="flex items-center w-full">
                                        <label
                                            for="profile_image"
                                            class="flex flex-col w-full h-20 border-4 border-blue-200 border-dashed hover:bg-gray-100 hover:border-gray-300"
                                        >
                                            <div class="flex flex-col items-center justify-center pt-3">
                                                <x-svg.file-upload/>
                                                <div x-show="!profileImage">
                                                    Attach a file
                                                </div>
                                                <div x-show="profileImage" x-text="profileImage">
                                                </div>
                                            </div>
                                            <input
                                                @change="profileImage = $event.target.value"
                                                id="profile_image"
                                                type="file"
                                                name="profile_image"
                                                class="opacity-0"
                                                required
                                            >
                                        </label>
                                    </div>
                                </div>
                                <x-error field="profile_image"/>
                            </x-label>
                        </div>

                        <div class="flex space-x-32 mt-6 max-w-md">
                            <a
                                @turboReload
                                class="py-2 px-4 text-sm rounded-md bg-white border-4 border-opacity-20 hover:bg-gray-300 focus:outline-none"
                                href="{{ route('admin.users.show', $user) }}"
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
