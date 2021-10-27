<x-layouts.guest>
    <x-auth-session-status :status="session('status')"/>

    <form method="POST" action="{{ route('register') }}" class="mt-4">
        @csrf

        <x-label for="email" :value="trans_validation_attribute('email')" class="label">
            <x-input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                class="form-input mt-1 block w-full rounded-md focus:border-blue-600"
                required
                autofocus
            />
            <x-error field="email"/>
        </x-label>

        <x-label for="password" :value="trans_validation_attribute('password')" class="label mt-3">
            <x-input
                id="password"
                class="form-input mt-1 block w-full rounded-md focus:border-blue-600"
                type="password"
                name="password"
                required
            />
            <x-error field="password"/>
        </x-label>

        <x-label for="password_confirmation" :value="trans_validation_attribute('password_confirmation')" class="label mt-3">
            <x-input
                id="password_confirmation"
                class="form-input mt-1 block w-full rounded-md focus:border-blue-600"
                type="password"
                name="password_confirmation"
                required
            />
            <x-error field="password_confirmation"/>
        </x-label>

        <div class="flex justify-between items-center mt-4">
            <div>
                <a
                    href="{{ route('password.request') }}"
                    class="block text-sm fontme text-blue-700 hover:underline"
                >
                    {{ __('Already registered?') }}
                </a>
            </div>
        </div>

        <div class="mt-6">
            <x-button  class="bg-blue-600 hover:bg-blue-500">
                {{ __('Register') }}
            </x-button>
        </div>
    </form>
</x-layouts.guest>
