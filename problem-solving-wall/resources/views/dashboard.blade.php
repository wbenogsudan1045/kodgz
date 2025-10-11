<x-app-layout>
    <div class="p-6">
        <div class="p-6 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-700">Home Screen</h1>
        </div>

        <!-- Boards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-6 max-w-6xl mx-auto">
            @foreach($boards as $board)
                <x-cards :board="$board" />
            @endforeach
        </div>

        <!-- Floating Button -->
        <div class="fixed bottom-6 right-6">
            <x-addboardbutton />
        </div>
    </div>

    <!-- Password Modal -->
    <x-board-password />
</x-app-layout>