<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-semibold text-sm text-green-500">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form id="resend_verification" method="POST" action="{{ route('verification.send') }}" class="mt-4">
            @csrf

            <div>
                <x-button  class="bg-blue-600 hover:bg-blue-500">
                    {{ __('Resend Verification Email') }}
                </x-button>
            </div>
        </form>

        <form id="logout" method="POST" action="{{ route('logout') }}" class="mt-4">
            @method('DELETE')
            @csrf

            <x-button class="bg-gray-600 hover:bg-gray-500">
                {{ __('Log Out') }}
            </x-button>
        </form>
    </div>
</x-guest-layout>
