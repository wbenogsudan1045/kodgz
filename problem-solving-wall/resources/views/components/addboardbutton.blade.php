<div x-data="{ open: false }">
    <!-- ✅ Floating Add Board Button -->
    <button @click="open = true" class="fixed bottom-6 right-6 flex items-center justify-center
               w-12 h-12 rounded-full shadow-lg cursor-pointer
               bg-green-300 hover:bg-green-400
               transition duration-200 ease-in-out z-50">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            class="w-6 h-6 text-black">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </button>

    <!-- ✅ Create Board Modal -->
    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" x-cloak>
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">
            <!-- Close button -->
            <button @click="open = false"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold leading-none">
                &times;
            </button>

            <h2 class="text-lg font-bold mb-4">Create New Board</h2>

            <form method="POST" action="{{ route('boards.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium">Board Name</label>
                    <input type="text" name="name" class="w-full border rounded p-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Password (optional)</label>
                    <input type="text" name="password" class="w-full border rounded p-2">
                    <p class="text-xs text-gray-500">Leave blank to allow anyone to join.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Description</label>
                    <textarea name="description" class="w-full border rounded p-2"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Thumbnail</label>
                    <input type="file" name="thumbnail" class="w-full">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>