<!-- Password Modal -->
<div id="passwordModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4 border border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-800">Enter Board Password</h2>
            <button onclick="closePasswordModal()" class="text-gray-500 hover:text-[#86efac] transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="passwordForm" onsubmit="submitPassword(event)">
            @csrf
            <input type="hidden" id="boardId" name="board_id">

            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 rounded-lg bg-gray-100 border border-gray-300 text-gray-800 placeholder-gray-400 
                           focus:ring-2 focus:ring-[#86efac] focus:border-transparent transition"
                    placeholder="Enter password" required>
                <p id="errorMessage" class="text-red-500 text-sm mt-2 hidden"></p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closePasswordModal()"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-[#86efac] text-gray-900 font-medium rounded-lg hover:bg-[#6ee7b7] shadow-md transition">
                    Enter
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPasswordModal(boardId) {
        document.getElementById('boardId').value = boardId;
        document.getElementById('password').value = '';
        document.getElementById('errorMessage').classList.add('hidden');
        document.getElementById('passwordModal').classList.remove('hidden');
        document.getElementById('passwordModal').classList.add('flex');
    }

    function closePasswordModal() {
        document.getElementById('passwordModal').classList.add('hidden');
        document.getElementById('passwordModal').classList.remove('flex');
    }

    async function submitPassword(event) {
        event.preventDefault();

        const boardId = document.getElementById('boardId').value;
        const password = document.getElementById('password').value;
        const errorMessage = document.getElementById('errorMessage');

        try {
            const response = await fetch(`/boards/${boardId}/verify-password`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ password })
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = `/boards/${boardId}`;
            } else {
                errorMessage.textContent = data.message || 'Incorrect password';
                errorMessage.classList.remove('hidden');
            }
        } catch (error) {
            errorMessage.textContent = 'An error occurred. Please try again.';
            errorMessage.classList.remove('hidden');
        }
    }

    // Close modal when clicking outside
    document.getElementById('passwordModal')?.addEventListener('click', function (e) {
        if (e.target === this) closePasswordModal();
    });
</script>