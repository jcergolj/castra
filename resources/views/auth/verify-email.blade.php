<x-layouts.guest>
    <div class="mb-4 text-sm text-gray-600">
            {{
                __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.')
            }}
    </div>

    <x-auth-session-status :status="session('status')"/>

    <div class="mt-4 flex items-center justify-between">
        <form
            id="resend_verification"
            method="POST"
            action="{{ route('verification.send') }}"
            class="mt-4"
        >
            @csrf

            <div>
                <x-button  class="w-full text-sm">
                    {{ __('Resend Verification Email') }}
                </x-button>
            </div>
        </form>

        <form id="logout" method="POST" action="{{ route('logout') }}" class="mt-4">
            @method('DELETE')
            @csrf

            <x-button class="w-full text-sm">
                {{ __('Log Out') }}
            </x-button>
        </form>
    </div>
</x-layouts.guest>
