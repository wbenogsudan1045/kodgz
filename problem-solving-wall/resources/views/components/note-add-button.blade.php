<div x-data="{ open: false, notes: [] }" x-init="
    fetch('/boards/{{ $board->id }}/notes-list')
        .then(res => res.json())
        .then(data => notes = data.notes || [])
        .catch(()=> notes = [])
">
    <!-- Floating Add Button -->
    <button @click="open = true"
        class="fixed bottom-6 right-6 w-12 h-12 rounded-full bg-green-400 flex items-center justify-center shadow-lg hover:bg-green-500 transition">
        <svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6 text-black' fill='none' viewBox='0 0 24 24'
            stroke='currentColor'>
            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4' />
        </svg>
    </button>

    <!-- Modal Overlay -->
    <div x-show="open" x-cloak class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 relative">
            <button @click="open = false"
                class="absolute top-2 right-3 text-gray-400 hover:text-gray-600 text-2xl font-bold leading-none">&times;</button>

            <h2 class="text-xl font-bold mb-4">Add Sticky Note</h2>

            <form method="POST" action="{{ route('boards.notes.store', $board->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" class="w-full border rounded-md px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" class="w-full border rounded-md px-3 py-2" required></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Color</label>
                    <select name="color" class="w-full border rounded-md px-3 py-2" required>
                        <option value="yellow" selected>Yellow</option>
                        <option value="red">Red</option>
                        <option value="green">Green</option>
                    </select>
                </div>

                <!-- Attachment -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Attachment (optional)</label>
                    <input type="file" name="attachment" class="w-full border rounded-md px-3 py-2">
                </div>

                <!-- Link to Existing Note (by id) -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Link to Note (optional)</label>
                    <select name="linked_note_id" class="w-full border rounded-md px-3 py-2 text-sm text-gray-700">
                        <option value="">— None —</option>
                        <template x-for="n in notes" :key="n.id">
                            <option :value="n.id" x-text="n.title || 'Untitled'"></option>
                        </template>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                        Save Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>