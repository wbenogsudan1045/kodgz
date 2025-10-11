<x-app-layout>
    <div class="py-8 px-6 bg-green-50 min-h-screen">
        <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-md p-8 relative">

            <!-- Profile Header -->
            <div class="flex items-center justify-between border-b pb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-4xl font-bold">
                        <i class="fa-regular fa-user text-gray-600"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">{{ $user->name }}</h1>
                        <p class="text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}"
                    class="px-4 py-2 bg-green-400 text-white font-semibold rounded-lg hover:bg-green-500 transition">
                    Edit Profile
                </a>
            </div>

            <!-- Boards Summary -->
            <div class="flex justify-center space-x-8 mt-8">
                <div class="bg-green-200 px-8 py-4 rounded-lg text-center shadow">
                    <p class="text-gray-600 font-medium">Boards Entered</p>
                    <h2 class="text-2xl font-bold">{{ $boardsEntered }}</h2>
                </div>
                <div class="bg-green-200 px-8 py-4 rounded-lg text-center shadow">
                    <p class="text-gray-600 font-medium">Boards Made</p>
                    <h2 class="text-2xl font-bold">{{ $boardsMade }}</h2>
                </div>
            </div>

            <!-- Main Layout -->
            <div class="mt-10 grid grid-cols-3 gap-6 relative">
                <!-- Left Section: Notes Area -->
                <div class="col-span-2 relative border rounded-lg bg-gray-50 h-[500px] overflow-hidden"
                    id="notes-container">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2 pl-4 pt-3">Recently made notes:</h2>

                    @forelse ($user->stickyNotes()->latest()->take(12)->get() as $note)
                        <div class="note absolute cursor-move rounded-lg shadow-lg p-2 text-center text-sm font-medium transition-transform hover:scale-105"
                            title="{{ $note->content }}" style="
                                    background-color: {{ $note->color }};
                                    top: {{ $note->pos_y ?? rand(80, 400) }}px;
                                    left: {{ $note->pos_x ?? rand(40, 600) }}px;
                                    width: 100px;
                                    height: 100px;
                                    word-wrap: break-word;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                    color: black;
                                " data-id="{{ $note->id }}" data-title="{{ $note->title }}" data-color="{{ $note->color }}"
                            data-author="{{ $note->user->name }}" data-content="{{ $note->content }}">
                            <p class="leading-snug break-words text-center w-full h-full overflow-hidden text-ellipsis"
                                style="color: black;">
                                {{ Str::limit($note->content, 80) }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 p-4">No notes yet.</p>
                    @endforelse
                </div>

                <!-- Right Section: Color Summary -->
                <div class="flex flex-col items-center space-y-4">
                    <div class="bg-green-200 px-8 py-4 rounded-lg w-48 text-center shadow">
                        <p class="font-medium text-gray-700">Green Notes</p>
                        <h2 class="text-2xl font-bold">{{ $greenNotes }}</h2>
                    </div>
                    <div class="bg-red-500 text-white px-8 py-4 rounded-lg w-48 text-center shadow">
                        <p class="font-medium">Red Notes</p>
                        <h2 class="text-2xl font-bold">{{ $redNotes }}</h2>
                    </div>
                    <div class="bg-yellow-300 px-8 py-4 rounded-lg w-48 text-center shadow">
                        <p class="font-medium text-gray-800">Yellow Notes</p>
                        <h2 class="text-2xl font-bold">{{ $yellowNotes }}</h2>
                    </div>
                </div>
            </div>

            <!-- Hidden Sidebar Elements (for board-script) -->
            <div class="hidden">
                <h3 id="sidebar-title"></h3>
                <p id="sidebar-color"></p>
                <p id="sidebar-author"></p>
                <p id="sidebar-description"></p>
            </div>
        </div>
    </div>

    <!-- Include existing draggable script -->
    @include('components.board-script')
</x-app-layout>