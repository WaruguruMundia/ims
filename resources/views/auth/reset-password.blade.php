<x-guest-layout>
    @if (isset($expires))
        <div class="mb-4 p-4 bg-indigo-50 border border-indigo-100 rounded-lg text-sm text-indigo-700 flex items-center justify-between shadow-sm" id="expiry-timer-container">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ __('This password reset link will expire in:') }}</span>
            </div>
            <span class="font-mono font-bold text-lg bg-white px-2 py-0.5 rounded border border-indigo-200 text-indigo-800 tabular-nums shadow-inner" id="expiry-timer">--:--</span>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const expiresTimestamp = parseInt("{{ $expires }}") * 1000;
                const timerElement = document.getElementById('expiry-timer');

                function updateTimer() {
                    const now = new Date().getTime();
                    const distance = expiresTimestamp - now;

                    if (distance <= 0) {
                        timerElement.innerHTML = "00:00";
                        clearInterval(timerInterval);
                        window.location.reload();
                        return;
                    }

                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    timerElement.innerHTML = 
                        String(minutes).padStart(2, '0') + ":" + 
                        String(seconds).padStart(2, '0');
                }

                updateTimer();
                const timerInterval = setInterval(updateTimer, 1000);
            });
        </script>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
