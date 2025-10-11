<div id="note-sidebar" class="w-72 h-full bg-gray-100 flex flex-col p-4 overflow-y-auto" x-data="noteSidebar">
    <h2 class="text-lg font-bold mb-4 text-black">Details</h2>

    <!-- Sidebar Content -->
    <div id="sidebar-content" class="flex-1 space-y-3 text-sm text-black"
        style="word-wrap: break-word; white-space: normal; overflow-wrap: anywhere;">
        <p><span class="font-semibold">Title:</span> <span x-text="title || '—'"></span></p>
        <p><span class="font-semibold">Color:</span> <span x-text="color || '—'"></span></p>
        <p><span class="font-semibold">Author:</span> <span x-text="author || '—'"></span></p>
        <div>
            <p class="font-semibold">Description:</p>
            <p class="mt-1 text-gray-800" x-text="content || '—'"></p>
        </div>

        <template x-if="attachment">
            <div class="text-sm opacity-90 mt-4">
                <p class="font-semibold mb-1">Attachment:</p>
                <template x-if="isImage(attachment)">
                    <img :src="attachment" alt="Attachment" class="rounded-lg shadow-md max-h-48 object-contain">
                </template>
                <template x-if="!isImage(attachment)">
                    <a :href="attachment" target="_blank" class="text-blue-600 underline">
                        Download Attachment
                    </a>
                </template>
            </div>
        </template>
    </div>

    <!-- Buttons -->
    <div class="mt-6">
        <button @click="openPreview()" :disabled="!hasSelectedNote"
            class="w-full font-semibold py-2 rounded-lg transition text-black disabled:opacity-50 disabled:cursor-not-allowed"
            :style="hasSelectedNote ? 'background-color: #86efac;' : 'background-color: #d1d5db;'">
            Preview Note
        </button>

        <div class="mt-4 flex gap-2">
            <button @click="editNote()" :disabled="!hasSelectedNote"
                class="flex-1 font-semibold py-2 rounded-lg transition text-black disabled:opacity-50 disabled:cursor-not-allowed"
                :style="hasSelectedNote ? 'background-color: #facc15;' : 'background-color: #d1d5db;'">
                Edit Note
            </button>

            <button @click="archiveNote()" :disabled="!hasSelectedNote"
                class="flex-1 font-semibold py-2 rounded-lg transition text-black disabled:opacity-50 disabled:cursor-not-allowed"
                :style="hasSelectedNote ? 'background-color: #f87171;' : 'background-color: #d1d5db;'">
                Delete
            </button>
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-show="open"
        class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center backdrop-blur-sm z-50" x-cloak>
        <div class="rounded-xl shadow-lg w-96 p-6 relative transition text-black"
            :style="`background-color: ${color};`">
            <button @click="open = false"
                class="absolute top-2 right-3 text-2xl font-bold leading-none text-black">&times;</button>
            <h2 class="text-xl font-bold mb-3" x-text="title"></h2>
            <p class="text-sm mb-4" x-text="content"></p>
            <div class="text-sm opacity-90">
                <p><span class="font-semibold">Author:</span> <span x-text="author"></span></p>
                <p><span class="font-semibold">Board:</span> <span x-text="board"></span></p>
                <p><span class="font-semibold">Color:</span> <span x-text="color"></span></p>
            </div>
            <div class="text-sm opacity-90 mt-4" x-show="attachment">
                <p class="font-semibold mb-1">Attachment:</p>
                <template x-if="isImage(attachment)">
                    <img :src="attachment" alt="Attachment" class="rounded-lg shadow-md max-h-48 object-contain">
                </template>
                <template x-if="!isImage(attachment)">
                    <a :href="attachment" target="_blank" class="text-blue-600 underline">Download Attachment</a>
                </template>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editNoteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50">
        <div class="bg-white rounded-2xl p-6 w-96 shadow-lg border-t-4 border-green-300">
            <h2 class="text-lg font-semibold text-green-800 mb-3">Edit Sticky Note</h2>

            <form id="editNoteForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="editNoteId">

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="editTitle"
                        class="w-full border rounded p-2 focus:border-green-400 focus:ring-green-300" />
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea id="editContent"
                        class="w-full border rounded p-2 focus:border-green-400 focus:ring-green-300"></textarea>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Color</label>
                    <!-- Dropdown with only red/yellow/green -->
                    <select id="editColor" class="w-full border rounded p-2">
                        <option value="yellow">Yellow</option>
                        <option value="red">Red</option>
                        <option value="green">Green</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Attachment</label>
                    <input type="file" id="editAttachment"
                        class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200" />
                    <template x-if="attachment">
                        <div class="mt-2 text-sm text-gray-700">
                            Current file: <a :href="attachment" target="_blank" class="underline">View</a>
                        </div>
                    </template>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="cancelEditBtn"
                        class="px-3 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-300 text-green-900 font-semibold rounded-lg hover:bg-green-400">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine Logic -->
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("noteSidebar", () => ({
                open: false,
                hasSelectedNote: false,
                color: "#ffffff",
                title: "",
                content: "",
                author: "",
                board: "{{ $board->name ?? '—' }}",
                attachment: null,
                noteId: null,
                notes: [],

                init() {
                    const notes = document.querySelectorAll(".note");
                    this.notes = Array.from(notes).map(n => ({
                        id: n.dataset.id,
                        title: n.dataset.title,
                        content: n.dataset.content,
                        color: n.dataset.color,
                        author: n.dataset.author,
                        attachment: n.dataset.attachment
                    }));

                    notes.forEach(note => {
                        note.addEventListener("click", () => {
                            this.noteId = note.dataset.id;
                            this.title = note.dataset.title;
                            this.content = note.dataset.content;
                            this.color = note.dataset.color;
                            this.author = note.dataset.author;
                            this.attachment = note.dataset.attachment;
                            this.hasSelectedNote = true;
                        });
                    });
                },

                isImage(url) {
                    return url && /\.(jpeg|jpg|gif|png|webp)$/i.test(url);
                },

                openPreview() {
                    if (this.hasSelectedNote) this.open = true;
                },

                editNote() {
                    if (!this.noteId) return alert("No note selected!");
                    const note = this.notes.find(n => n.id == this.noteId);
                    if (!note) return alert("Note not found!");

                    document.getElementById("editNoteId").value = note.id;
                    document.getElementById("editTitle").value = note.title || "";
                    document.getElementById("editContent").value = note.content || "";
                    document.getElementById("editColor").value = note.color || "yellow";

                    // The sidebar's attachment variable is already populated,
                    // but also set the file preview for the modal (the modal JS reads `attachment` on save)
                    this.attachment = note.attachment || null;

                    document.getElementById("editNoteModal").classList.remove("hidden");
                },

                archiveNote() {
                    if (!this.noteId) return alert("No note selected!");
                    if (confirm("Are you sure you want to archive this note?")) {
                        fetch(`/notes/${this.noteId}/archive`, {
                            method: "POST",
                            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                        })
                            .then(res => res.json())
                            .then(() => {
                                alert("✅ Note archived successfully!");
                                document.querySelector(`.note[data-id='${this.noteId}']`)?.remove();
                                this.resetSidebar();
                            })
                            .catch(() => alert("❌ Failed to archive note."));
                    }
                },

                resetSidebar() {
                    this.noteId = null;
                    this.title = "";
                    this.content = "";
                    this.color = "#ffffff";
                    this.author = "";
                    this.attachment = null;
                    this.hasSelectedNote = false;
                },
            }));
        });

        // Modal Logic for Editing
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("editNoteModal");
            const cancelBtn = document.getElementById("cancelEditBtn");

            cancelBtn.addEventListener("click", () => modal.classList.add("hidden"));

            document.getElementById("editNoteForm").addEventListener("submit", async (e) => {
                e.preventDefault();

                const id = document.getElementById("editNoteId").value;
                const title = document.getElementById("editTitle").value;
                const content = document.getElementById("editContent").value;
                const color = document.getElementById("editColor").value;
                const attachment = document.getElementById("editAttachment").files[0];

                const formData = new FormData();
                formData.append("title", title);
                formData.append("content", content);
                formData.append("color", color);
                if (attachment) formData.append("attachment", attachment);

                // NOTE: DO NOT set Content-Type when sending FormData. Only include CSRF header.
                const response = await fetch(`/notes/${id}/update`, {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                });

                if (response.ok) {
                    alert("✅ Note updated successfully!");
                    modal.classList.add("hidden");

                    // update the DOM instantly
                    const noteDiv = document.querySelector(`.note[data-id='${id}']`);
                    if (noteDiv) {
                        noteDiv.dataset.title = title;
                        noteDiv.dataset.content = content;
                        noteDiv.dataset.color = color;
                        noteDiv.style.backgroundColor = color;
                    }
                } else {
                    // try to parse JSON error message if any
                    let errMsg = "Failed to update note.";
                    try {
                        const json = await response.json();
                        if (json?.error) errMsg = json.error;
                        if (json?.message) errMsg = json.message;
                    } catch (err) { /* ignore parse */ }
                    alert("❌ " + errMsg);
                }
            });
        });
    </script>
</div>