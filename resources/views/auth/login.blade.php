<x-layouts.guest>
    <form method="POST" action="{{ route('login') }}" class="mt-4">
        @csrf

        <x-label for="email" :value="trans_validation_attribute('email')" class="label block">
            <x-input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                class="mt-1 block"
                required
                autofocus
            />
            <x-error field="email"/>
        </x-label>

        <x-label
            for="password"
            :value="trans_validation_attribute('password')"
            class="label block mt-3"
        >
            <x-input
                id="password"
                class="mt-1 block"
                type="password"
                name="password"
                required
            />
            <x-error field="password"/>
        </x-label>

        <div class="flex justify-between items-center mt-4">
            <div>
                <x-label-checkbox
                    for="remember"
                    :value="trans_validation_attribute('remember')"
                    class="inline-flex items-center"
                >
                    <input type="checkbox" name="remember" class="form-checkbox text-blue-600">
                    <x-error field="remember"/>
                </x-label-checkbox>
            </div>

            @if (Route::has('password.request'))
                <div>
                    <a
                        href="{{ route('password.request') }}"
                        class="block text-sm fontme text-blue-700 hover:underline"
                    >
                        {{ __('Forgot your password?') }}
                    </a>
                </div>
            @endif
        </div>

        <div class="mt-6">
            <x-button  class="w-full text-sm">
                {{ __('Log in') }}
            </x-button>
        </div>
    </form>
</x-layouts.guest>
