<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-green-300 p-4">
        <div class="bg-white/30 p-10 rounded-[20px] shadow-lg w-full max-w-xl relative">

            <!-- Logo (top, centered above the form) -->
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-black flex items-center justify-center text-white rounded">
                    Logo
                </div>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4 px-5">
                @csrf

                <!-- Username -->
                <div>
                    <label for="email" class="block mb-1 text-sm font-medium">Email:</label>
                    <input id="email" type="email" name="email" required autofocus
                        class="w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-green-400" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block mb-1 text-sm font-medium">Password:</label>
                    <input id="password" type="password" name="password" required
                        class="w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-green-400" />
                </div>

                <!-- Forgot Password -->
                @if (Route::has('password.request'))
                    <div class="flex items-center justify-end mt-2">
                        <a class="text-sm text-green-700 hover:underline" href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                    </div>
                @endif

                <!-- Button -->
                <div class="flex justify-center mt-4">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full shadow-md transition">
                        Log In
                    </button>
                </div>
            </form>

            <!-- Register Link -->
            <p class="p-4 text-sm text-center">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-green-700 font-semibold hover:underline">
                    Register Here
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>