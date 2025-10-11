<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-green-300 p-4">
        <div class="bg-white/30 backdrop-blur-md p-10 rounded-2xl shadow-lg w-full max-w-xl relative">

            <!-- Logo (top, centered) -->
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-black flex items-center justify-center text-white rounded">
                    Logo
                </div>
            </div>

            <!-- Description -->
            <div class="px-4 mb-6 text-sm text-gray-700 text-center">
                {{ __('Forgot your password? No problem. Just enter your email and we will send you a reset link.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="yx-4 text-red mb-4 text-center" :status="session('status')" />

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4 px-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block mb-1 text-sm font-medium">Email:</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus
                        class="w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-green-400" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Button -->
                <div class="flex justify-center mt-4">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full shadow-md transition">
                        Email Password Reset Link
                    </button>
                </div>
            </form>

            <!-- Back to Login -->
            <p class="p-4 text-sm text-center">
                Remember your password?
                <a href="{{ route('login') }}" class="text-green-700 font-semibold hover:underline">
                    Log In
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>