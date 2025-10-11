<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold mb-4">Search Results for "{{ $query }}"</h2>

        @if($boards->count() > 0)
            <div class="bg-white shadow rounded-lg divide-y">
                @foreach($boards as $board)
                    <div class="p-4 flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-lg">{{ $board->name }}</h3>
                            <p class="text-gray-500 text-sm">
                                {{ $board->description ?? 'No description available' }}
                            </p>
                        </div>

                        @if($board->users->contains(auth()->id()))
                            <a href="{{ route('boards.show', $board->id) }}"
                                class="px-3 py-1 bg-green-500 text-white rounded-md text-sm">
                                Open
                            </a>
                        @else
                            <form action="{{ route('boards.join', $board->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1 bg-yellow-500 text-white rounded-md text-sm hover:bg-yellow-600">
                                    Join
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No boards found matching your search.</p>
        @endif
    </div>
</x-app-layout>