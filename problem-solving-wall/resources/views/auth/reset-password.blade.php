<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-green-300 p-4">
        <div class="bg-white/100 p-10 rounded-[20px] shadow-lg w-full max-w-xl relative">

            <!-- Logo (top, centered above the form) -->
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-black flex items-center justify-center text-white rounded">
                    Logo
                </div>
            </div>

            <!-- Status Message -->
            @if (session('status'))
                <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-800 text-sm font-medium shadow">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.store') }}" class="space-y-4 px-5 ">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label for="email" class="block mb-1 text-sm font-medium">Email:</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                        autofocus
                        class="w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-green-400 bg-white text-black" />
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block mb-1 text-sm font-medium">New Password:</label>
                    <input id="password" type="password" name="password" required
                        class="w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-green-400 bg-white text-black" />
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block mb-1 text-sm font-medium">Confirm Password:</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:ring-2 focus:ring-green-400 bg-white text-black" />
                    @error('password_confirmation')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Button -->
                <div class="flex justify-center mt-4">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full shadow-md transition">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>