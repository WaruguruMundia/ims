<x-guest-layout>
    <div class="mb-4 text-sm text-gray-650">
        {{ __('Pre-registered Intern? Enter your registered email address below, and we will email you a secure link to verify your account and set up your password.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('activate.send') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Registered Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already active? Login') }}
            </a>

            <x-primary-button>
                {{ __('Send Activation Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
