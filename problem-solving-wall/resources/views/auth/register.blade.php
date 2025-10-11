<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-green-300 p-4">
        <div class="bg-white/30 p-10 rounded-[20px] shadow-lg w-full max-w-xl relative">

            <!-- Logo (top, centered above the form instead of overlapping) -->
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-black flex items-center justify-center text-white rounded">
                    Logo
                </div>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4 px-5">
                @csrf

                <!-- Full Name -->
                <div>
                    <label for="name" class="block mb-1 text-sm font-medium">Full Name:</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-green-400" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-1 text-sm font-medium">Email:</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-green-400" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block mb-1 text-sm font-medium">Password:</label>
                    <input id="password" type="password" name="password" required
                        class="w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-green-400" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block mb-1 text-sm font-medium">Confirm Password:</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-green-400" />
                </div>

                <!-- Button -->
                <div class="flex justify-center mt-4">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full shadow-md transition">
                        Register
                    </button>
                </div>
            </form>

            <!-- Already Registered -->
            <p class="p-4 text-sm text-center">
                Already have an account?
                <a href="{{ route('login') }}" class="text-green-700 font-semibold hover:underline">
                    Log In
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>