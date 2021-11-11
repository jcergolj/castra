<x-layouts.guest>
    <div class="mb-4 text-sm text-gray-600">
        Forgot your password?
        No problem.
        Just let us know your email address and we will email
         you a password reset link that will allow you to choose a new one.
    </div>
    <x-auth-session-status :status="session('status')"/>

    <form method="POST" action="{{ route('password.email') }}" class="mt-4">
        @csrf
        <x-label for="email" :value="trans_validation_attribute('email')" class="block">
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

        <div class="mt-6">
            <x-button  class="w-full text-sm">
                Email Password Reset Link
            </x-button>
        </div>
    </form>
</x-layouts.guest>
