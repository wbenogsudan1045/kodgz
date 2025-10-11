@props(['board'])

<div class="board-card" data-board-id="{{ $board->id }}" data-has-password="{{ $board->password ? 'true' : 'false' }}">
    <a href="{{ $board->password ? '#' : route('boards.show', $board->id) }}"
        class="bg-pink-100 rounded-lg shadow-md overflow-hidden flex flex-col hover:shadow-lg transition"
        onclick="{{ $board->password ? 'event.preventDefault(); openPasswordModal(' . $board->id . ')' : '' }}">
        <div class="bg-pink-100 rounded-xl shadow-md overflow-hidden flex flex-col hover:shadow-lg transition">
            <!-- Thumbnail -->
            <div class="h-40 w-full">
                @if($board->thumbnail)
                    <img src="{{ asset('storage/' . $board->thumbnail) }}" alt="{{ $board->name }}"
                        class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500 text-sm">No Image</span>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="bg-pink-200 px-4 py-3 flex items-center justify-between">
                <span class="text-sm font-medium text-gray-800 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ $board->name }}
                </span>
                @if($board->password)
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                @endif
            </div>
        </div>
    </a>
</div>