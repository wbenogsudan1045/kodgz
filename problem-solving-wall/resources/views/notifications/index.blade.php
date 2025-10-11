<x-app-layout>
    <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Notifications</h2>
        <div class="bg-white shadow rounded-lg p-4">
            @forelse($notifications as $notification)
                <div class="border-b py-2">
                    <p class="text-gray-700 text-sm">
                        {{ $notification->data['message'] }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Board: {{ $notification->data['board_name'] }} |
                        {{ $notification->created_at->diffForHumans() }}
                    </p>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No notifications yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
