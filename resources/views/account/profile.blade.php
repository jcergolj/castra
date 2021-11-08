<x-layouts.auth>
    <h3 class="text-gray-700 text-3xl font-semibold">Your profile</h3>
    <div class="mt-8">
        <div class="mt-4">
            <div class="p-6 bg-white rounded-md shadow-md">
                <h2 class="text-lg text-gray-700 font-semibold capitalize">Password</h2>
                <turbo-frame id="frame_update_password">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-4">
                        <div class="text-gray-700">Password:</div>
                        <div class="text-gray-700">********</div>
                        <a href="{{ route('account.password.edit') }}">
                            <x-svg.edit title="update password"/>
                        </a>
                        <x-status-inline/>
                    </div>
                </turbo-frame>
            </div>
        </div>

        <div class="mt-4">
            <div class="p-6 bg-white rounded-md shadow-md">
                <h2 class="text-lg text-gray-700 font-semibold capitalize">Email</h2>
                <turbo-frame id="frame_update_email">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-4">
                        <div class="text-gray-700">Email:</div>
                        <div class="text-gray-700">{{ user()->email }}</div>
                        <a href="{{ route('account.email.edit') }}">
                            <x-svg.edit title="update password"/>
                        </a>
                        <x-status-inline/>
                    </div>
                </turbo-frame>
            </div>
        </div>
    </div>
</x-layouts.auth>
