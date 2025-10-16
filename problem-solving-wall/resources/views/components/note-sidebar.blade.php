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

        <!-- Linked Notes -->
        <div x-show="linkedNotes.length > 0" class="mt-4">
            <p class="font-semibold">Linked Notes:</p>
            <ul class="list-disc ml-5 text-gray-700">
                <template x-for="link in linkedNotes" :key="link.id">
                    <li>
                        <span x-text="link.title"></span>
                        <span class="italic text-gray-500 ml-1" x-show="link.relation_type">(
                            <span x-text="link.relation_type"></span>)</span>
                    </li>
                </template>
            </ul>
        </div>

        <!-- Attachment -->
        <template x-if="attachment">
            <div class="text-sm opacity-90 mt-4">
                <p class="font-semibold mb-1">Attachment:</p>
                <template x-if="isImage(attachment)">
                    <img :src="attachment" alt="Attachment" class="rounded-lg shadow-md max-h-48 object-contain">
                </template>
                <template x-if="!isImage(attachment)">
                    <a :href="attachment" target="_blank" class="text-blue-600 underline">Download Attachment</a>
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
            <button @click="editNote()" :disabled="!hasSelectedNote || !canEditOrDelete()"
                class="flex-1 font-semibold py-2 rounded-lg transition text-black disabled:opacity-50 disabled:cursor-not-allowed"
                :style="(hasSelectedNote && canEditOrDelete()) ? 'background-color: #facc15;' : 'background-color: #d1d5db;'">
                Edit Note
            </button>

            <button @click="archiveNote()" :disabled="!hasSelectedNote || !canEditOrDelete()"
                class="flex-1 font-semibold py-2 rounded-lg transition text-black disabled:opacity-50 disabled:cursor-not-allowed"
                :style="(hasSelectedNote && canEditOrDelete()) ? 'background-color: #f87171;' : 'background-color: #d1d5db;'">
                Delete
            </button>
        </div>
    </div>

    <!-- Edit Modal (Reordered + Relation Type Added) -->
    <div id="editNoteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50">
        <div class="bg-white rounded-2xl p-6 w-96 shadow-lg border-t-4 border-green-300 max-h-[90vh] overflow-y-auto">
            <h2 class="text-lg font-semibold text-green-800 mb-3">Edit Sticky Note</h2>
            <form id="editNoteForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="editNoteId">

                <!-- Title -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="editTitle" class="w-full border rounded p-2">
                </div>

                <!-- Content -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea id="editContent" class="w-full border rounded p-2"></textarea>
                </div>

                <!-- Color -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Color</label>
                    <select id="editColor" class="w-full border rounded p-2">
                        <option value="yellow">Yellow</option>
                        <option value="red">Red</option>
                        <option value="green">Green</option>
                    </select>
                </div>

                <!-- Link Note -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Link to Note on this Board</label>
                    <select id="linkNoteSelect" class="w-full border rounded p-2 text-sm"></select>
                </div>

                <!-- Relation Type -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Relation Type</label>
                    <select id="relationTypeSelect" class="w-full border rounded p-2 text-sm">
                        <option value="">— None —</option>
                        <option value="Problem">Problem</option>
                        <option value="Answer">Answer</option>
                    </select>
                    <button type="button" id="addLinkBtn"
                        class="mt-1 px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">
                        Add Link
                    </button>
                </div>

                <!-- Linked Notes List -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Linked Notes</label>
                    <div id="linkedNotesList" class="border rounded p-2 bg-gray-50 max-h-32 overflow-y-auto"></div>
                </div>

                <!-- Attachment (last) -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Attachment (optional)</label>
                    <input type="file" id="editAttachment" class="w-full border rounded p-2 text-sm">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="cancelEditBtn"
                        class="px-3 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-300 text-green-900 font-semibold rounded-lg hover:bg-green-400">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("noteSidebar", () => ({
                hasSelectedNote: false,
                title: "",
                content: "",
                color: "",
                author: "",
                attachment: null,
                noteId: null,
                currentUserId: {{ Auth::id() }},
                boardOwnerId: {{ $board->user_id }},
                noteAuthorId: null,
                linkedNotes: [],
                open: false,

                init() {
                    const notes = document.querySelectorAll(".note");
                    notes.forEach(note => {
                        note.addEventListener("click", () => {
                            this.noteId = note.dataset.id;
                            this.title = note.dataset.title;
                            this.content = note.dataset.content;
                            this.color = note.dataset.color;
                            this.author = note.dataset.author;
                            this.attachment = note.dataset.attachment;
                            this.noteAuthorId = note.dataset.authorId;
                            this.hasSelectedNote = true;
                            this.fetchLinkedNotes();
                        });
                    });
                },

                async fetchLinkedNotes() {
                    if (!this.noteId) return;
                    try {
                        const res = await fetch(`/notes/${this.noteId}/linked`);
                        const data = await res.json();
                        this.linkedNotes = data.links || [];
                    } catch (err) {
                        console.error("Failed to fetch linked notes", err);
                        this.linkedNotes = [];
                    }
                },

                isImage(url) {
                    return url && /\.(jpeg|jpg|gif|png|webp)$/i.test(url);
                },

                canEditOrDelete() {
                    return parseInt(this.currentUserId) === parseInt(this.noteAuthorId)
                        || parseInt(this.currentUserId) === parseInt(this.boardOwnerId);
                },

                openPreview() {
                    if (!this.hasSelectedNote) return;
                    this.open = true;
                },

                editNote() {
                    if (!this.hasSelectedNote) return;
                    if (!this.canEditOrDelete()) {
                        alert("You don't have permission to edit this note.");
                        return;
                    }
                    const modal = document.getElementById("editNoteModal");
                    document.getElementById("editNoteId").value = this.noteId || "";
                    document.getElementById("editTitle").value = this.title || "";
                    document.getElementById("editContent").value = this.content || "";
                    document.getElementById("editColor").value = this.color || "yellow";
                    loadLinkableNotes(this.noteId, {{ $board->id }});
                    refreshLinkedNotes(this.noteId);
                    modal.classList.remove("hidden");
                },

                async archiveNote() {
                    if (!this.hasSelectedNote) return;
                    if (!this.canEditOrDelete()) {
                        alert("You don't have permission to delete this note.");
                        return;
                    }
                    if (!confirm("Are you sure you want to delete (archive) this note?")) return;
                    try {
                        const res = await fetch(`/notes/${this.noteId}/archive`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                        });
                        if (res.ok) {
                            alert("Note archived.");
                            window.location.reload();
                        } else {
                            const json = await res.json().catch(() => ({}));
                            alert("Failed to archive note: " + (json.error || json.message || res.statusText));
                        }
                    } catch (err) {
                        console.error(err);
                        alert("Network error while archiving note.");
                    }
                },
            }));
        });

        // --- Linking logic ---
        async function loadLinkableNotes(currentNoteId, boardId) {
            try {
                const res = await fetch(`/boards/${boardId}/notes-list`);
                const data = await res.json();
                const select = document.getElementById("linkNoteSelect");
                select.innerHTML = "";
                data.notes.forEach(n => {
                    if (n.id != currentNoteId) {
                        const opt = document.createElement("option");
                        opt.value = n.id;
                        opt.textContent = n.title || "Untitled";
                        select.appendChild(opt);
                    }
                });
                const noneOpt = document.createElement("option");
                noneOpt.value = "";
                noneOpt.textContent = "— None —";
                select.insertBefore(noneOpt, select.firstChild);
            } catch (e) {
                console.error("Failed to load linkable notes", e);
            }
        }

        async function refreshLinkedNotes(currentNoteId) {
            const linkedList = document.getElementById("linkedNotesList");
            linkedList.innerHTML = "<span>Loading...</span>";
            const res = await fetch(`/notes/${currentNoteId}/linked`);
            const data = await res.json();
            linkedList.innerHTML = "";
            (data.links || []).forEach(link => {
                const li = document.createElement("div");
                li.className = "flex items-center justify-between mb-1";
                li.innerHTML = `
                    <span>${link.title || "Untitled"} <em class="text-gray-500 text-xs">(${link.relation_type || '—'})</em></span>
                    <button type="button" class="text-xs text-red-600 underline ml-2"
                        onclick="unlinkNote('${currentNoteId}', '${link.id}')">Remove</button>`;
                linkedList.appendChild(li);
            });
        }

        async function unlinkNote(currentNoteId, targetNoteId) {
            await fetch("/note-links/unlink", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    note_a_id: currentNoteId,
                    note_b_id: targetNoteId,
                }),
            });
            await refreshLinkedNotes(currentNoteId);
        }

        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("editNoteModal");
            const cancelBtn = document.getElementById("cancelEditBtn");

            if (cancelBtn) {
                cancelBtn.addEventListener("click", () => modal.classList.add("hidden"));
            }

            const addLinkBtn = document.getElementById("addLinkBtn");
            if (addLinkBtn) {
                addLinkBtn.onclick = async function () {
                    const currentNoteId = document.getElementById("editNoteId").value;
                    const targetNoteId = document.getElementById("linkNoteSelect").value;
                    const relationType = document.getElementById("relationTypeSelect").value;
                    if (!targetNoteId || targetNoteId == currentNoteId) return;
                    await fetch("/note-links", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            note_a_id: currentNoteId,
                            note_b_id: targetNoteId,
                            relation_type: relationType,
                        }),
                    });
                    await refreshLinkedNotes(currentNoteId);
                };
            }

            const editForm = document.getElementById("editNoteForm");
            if (editForm) {
                editForm.addEventListener("submit", async (e) => {
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

                    try {
                        const response = await fetch(`/notes/${id}/update`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            },
                            body: formData
                        });

                        if (response.ok) {
                            alert("Note updated successfully!");
                            modal.classList.add("hidden");
                            window.location.reload();
                        } else {
                            const json = await response.json().catch(() => null);
                            alert(json?.message || "Failed to update note.");
                        }
                    } catch (err) {
                        console.error("Update failed", err);
                        alert("Network error while updating note.");
                    }
                });
            }
        });
    </script>
</div>