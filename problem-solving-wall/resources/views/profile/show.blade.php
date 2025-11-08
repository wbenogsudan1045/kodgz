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

            <!-- Notes Area -->
            <div class="mt-10 grid grid-cols-3 gap-6 relative">
                <!-- Left Section -->
                <div class="col-span-2 relative border rounded-lg bg-gray-50 h-[500px] overflow-hidden"
                    id="notes-container">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2 pl-4 pt-3">Recently made notes:</h2>

                    @php
                        $myNotes = $user->stickyNotes()
                            ->where('user_id', auth()->id())
                            ->latest()
                            ->take(12)
                            ->get();
                    @endphp

                    @forelse ($myNotes as $note)
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
                            "
                            data-id="{{ $note->id }}">
                            <p class="leading-snug break-words text-center w-full h-full overflow-hidden text-ellipsis">
                                {{ Str::limit($note->content, 80) }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 p-4">No notes yet.</p>
                    @endforelse
                </div>

                <!-- Right Section -->
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
        </div>
    </div>

    <!-- 🧠 Independent Note Drag Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const notes = document.querySelectorAll(".note");
            const container = document.getElementById("notes-container");

            let activeNote = null;
            let offsetX = 0, offsetY = 0;

            notes.forEach(note => {
                note.addEventListener("mousedown", e => {
                    activeNote = note;
                    const rect = note.getBoundingClientRect();
                    const containerRect = container.getBoundingClientRect();
                    offsetX = e.clientX - rect.left;
                    offsetY = e.clientY - rect.top;
                    note.style.zIndex = 1000;
                    note.style.transition = "none";
                    e.preventDefault();
                });
            });

            document.addEventListener("mousemove", e => {
                if (!activeNote) return;
                const containerRect = container.getBoundingClientRect();
                const noteRect = activeNote.getBoundingClientRect();

                let x = e.clientX - containerRect.left - offsetX;
                let y = e.clientY - containerRect.top - offsetY;

                // Clamp inside the container boundaries
                const maxX = containerRect.width - noteRect.width;
                const maxY = containerRect.height - noteRect.height;
                x = Math.max(0, Math.min(x, maxX));
                y = Math.max(0, Math.min(y, maxY));

                activeNote.style.left = `${x}px`;
                activeNote.style.top = `${y}px`;
            });

            document.addEventListener("mouseup", () => {
                if (!activeNote) return;

                const noteId = activeNote.dataset.id;
                const x = parseFloat(activeNote.style.left);
                const y = parseFloat(activeNote.style.top);

                // Save note position
                fetch(`/notes/${noteId}/move`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ x, y })
                })
                    .then(res => res.json())
                    .then(data => console.log("✅ Note position saved:", data))
                    .catch(err => console.error("❌ Error saving position:", err));

                activeNote.style.zIndex = "";
                activeNote.style.transition = "all 0.1s ease-out";
                activeNote = null;
            });
        });
    </script>
</x-app-layout>
