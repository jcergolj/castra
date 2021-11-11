<x-layouts.auth>
    <h3 class="text-gray-700 text-3xl font-semibold">Your profile</h3>
    <div class="mt-8">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
            <div class="mt-4">
                <div class="p-6 bg-white rounded-md shadow-md">
                    <h2 class="text-lg text-gray-700 font-semibold capitalize">Profile Image</h2>
                    @include('accounts._image')
                </div>
            </div>

            <div class="mt-4">
                <div class="p-6 bg-white rounded-md shadow-md">
                    <h2 class="text-lg text-gray-700 font-semibold capitalize">Password</h2>
                    <turbo-frame id="frame_update_password">
                        <div class="grid grid-cols-6 gap-6 mt-4">
                            <div class="text-gray-700 col-span-5">********</div>
                            <a href="{{ route('accounts.passwords.edit') }}">
                                <x-svg.edit title="update password" />
                            </a>
                        </div>
                        <div class="grid grid-cols-1 mt-4">
                            <x-status.inline messageBag="update-password" />
                        </div>
                    </turbo-frame>
                </div>
            </div>

            <div class="mt-4">
                <div class="p-6 bg-white rounded-md shadow-md">
                    <h2 class="text-lg text-gray-700 font-semibold capitalize">Email</h2>
                    <turbo-frame id="frame_update_email">
                        <div class="grid grid-cols-6 gap-6 mt-4">
                            <div class="text-gray-700 col-span-5">{{ user()->email }}</div>
                            <a href="{{ route('accounts.emails.edit') }}">
                                <x-svg.edit title="update email" />
                            </a>
                        </div>
                        <div class="grid grid-cols-1 mt-4">
                            <x-status.inline messageBag="update-email" />
                        </div>
                    </turbo-frame>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth>
