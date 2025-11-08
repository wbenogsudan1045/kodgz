<style>
    /* === Custom Scrollbar Styling === */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #b9f6ca;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #34d399;
        border-radius: 10px;
        border: 2px solid #b9f6ca;
    }

    ::-webkit-scrollbar-thumb:hover {
        background-color: #10b981;
    }

    * {
        scrollbar-width: thin;
        scrollbar-color: #34d399 #b9f6ca;
    }
</style>

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
                        <span class="italic text-gray-500 ml-1" x-show="link.relation_type">
                            (<span x-text="link.relation_type"></span>)
                        </span>
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

    <!-- Sidebar Buttons -->
    <div class="mt-6">
        <button @click="openPreview()" :disabled="!hasSelectedNote"
            class="w-full font-semibold py-2 rounded-lg transition text-black disabled:opacity-50 disabled:cursor-not-allowed"
            :style="hasSelectedNote ? 'background-color: #86efac;' : 'background-color: #d1d5db;'">
            Preview Note
        </button>
    </div>

    <!-- ========================== -->
    <!-- 📄 Preview Modal -->
    <!-- ========================== -->
    <div x-show="open" x-transition id="previewNoteModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
        <div class="rounded-2xl p-6 w-[28rem] shadow-lg border-t-4 max-h-[90vh] overflow-y-auto relative" :style="{
        backgroundColor:
            color === 'yellow' ? '#FEF9C3' :
            color === 'red' ? '#FECACA' :
            color === 'green' ? '#BBF7D0' :
            '#FFFFFF',
        borderColor:
            color === 'yellow' ? '#FACC15' :
            color === 'red' ? '#F87171' :
            color === 'green' ? '#4ADE80' :
            '#D1D5DB'
    }">
            <button @click="open = false" class="absolute top-3 right-3 text-gray-700 hover:text-black">✕</button>

            <h2 class="text-xl font-semibold mb-4" :class="{
            'text-yellow-800': color === 'yellow',
            'text-red-800': color === 'red',
            'text-green-800': color === 'green'
        }" x-text="title || 'Untitled Note'"></h2>

            <div class="space-y-3 text-gray-900">
                <p><span class="font-semibold">Color:</span> <span x-text="color || '—'"></span></p>
                <p><span class="font-semibold">Author:</span> <span x-text="author || '—'"></span></p>

                <div>
                    <p class="font-semibold">Description:</p>
                    <p class="mt-1 whitespace-pre-wrap break-words" x-text="content || '—'"></p>
                </div>

                <div x-show="linkedNotes.length > 0" class="mt-4">
                    <p class="font-semibold">Linked Notes:</p>
                    <ul class="list-disc ml-5 text-gray-700">
                        <template x-for="link in linkedNotes" :key="link.id">
                            <li>
                                <span x-text="link.title"></span>
                                <span class="italic text-gray-500 ml-1" x-show="link.relation_type">
                                    (<span x-text="link.relation_type"></span>)
                                </span>
                            </li>
                        </template>
                    </ul>
                </div>

                <template x-if="attachment">
                    <div class="mt-4">
                        <p class="font-semibold">Attachment:</p>
                        <template x-if="isImage(attachment)">
                            <img :src="attachment" alt="Attachment Preview"
                                class="rounded-lg shadow-md mt-2 max-h-60 object-contain border border-gray-300">
                        </template>
                        <template x-if="!isImage(attachment)">
                            <a :href="attachment" target="_blank" class="text-blue-600 underline mt-2 block">
                                Download Attachment
                            </a>
                        </template>
                    </div>
                </template>
            </div>

            <!-- 🔽 Buttons aligned to bottom-right -->
            <div class="mt-6 flex justify-end items-center">
                <div class="flex gap-2" x-show="canEditOrDelete()">
                    <button @click="open = false; editNote()"
                        class="px-4 py-2 bg-yellow-400 text-yellow-900 font-semibold rounded-lg hover:bg-yellow-500">
                        <img src="{{ asset('icon/edit.png') }}" class="w-5 h-5" alt="Edit">
                    </button>
                    <button @click="archiveNote()"
                        class="px-4 py-2 bg-red-400 text-white font-semibold rounded-lg hover:bg-red-500">
                        <img src="{{ asset('icon/delete.png') }}" class="w-5 h-5" alt="Delete">
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- ========================== -->
    <!-- ✏️ Edit Note Modal -->
    <!-- ========================== -->
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

                <!-- Attachment -->
                <div class="mb-3">
                    <input type="file" id="editAttachment" class="w-full border rounded p-2 text-sm">
                    <label class="block text-sm font-medium text-gray-700">Attachment (optional)</label>
                    <div id="currentAttachmentContainer" class="mb-2 hidden">
                        <p class="text-sm text-gray-600">Current Attachment:</p>
                        <div class="flex items-center gap-2 mt-1">
                            <img id="currentAttachmentPreview" class="max-h-16 rounded shadow-sm border hidden">
                            <a id="currentAttachmentLink" href="#" target="_blank"
                                class="text-blue-600 underline text-sm hidden">View File</a>
                            <button type="button" id="removeAttachmentBtn"
                                class="text-xs text-red-600 underline ml-2">Remove</button>
                        </div>
                    </div>
                </div>

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

    <!-- ========================== -->
    <!-- 🔧 JavaScript Logic -->
    <!-- ========================== -->
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

                    const preview = document.getElementById("currentAttachmentPreview");
                    const link = document.getElementById("currentAttachmentLink");
                    const container = document.getElementById("currentAttachmentContainer");

                    if (this.attachment) {
                        container.classList.remove("hidden");
                        if (this.isImage(this.attachment)) {
                            preview.src = this.attachment;
                            preview.classList.remove("hidden");
                            link.classList.add("hidden");
                        } else {
                            link.href = this.attachment;
                            link.classList.remove("hidden");
                            preview.classList.add("hidden");
                        }
                    } else {
                        container.classList.add("hidden");
                    }

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

        // === Link Feature Logic ===
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
            const addLinkBtn = document.getElementById("addLinkBtn");
            const removeAttachmentBtn = document.getElementById("removeAttachmentBtn");
            const currentAttachmentContainer = document.getElementById("currentAttachmentContainer");
            const currentAttachmentPreview = document.getElementById("currentAttachmentPreview");
            const currentAttachmentLink = document.getElementById("currentAttachmentLink");
            const editForm = document.getElementById("editNoteForm");
            const editAttachmentInput = document.getElementById("editAttachment");

            if (cancelBtn) {
                cancelBtn.addEventListener("click", () => {
                    // hide modal and clear any temporary state if needed
                    modal.classList.add("hidden");
                });
            }

            if (addLinkBtn) {
                addLinkBtn.addEventListener("click", async () => {
                    const currentNoteId = document.getElementById("editNoteId").value;
                    const targetNoteId = document.getElementById("linkNoteSelect").value;
                    const relationType = document.getElementById("relationTypeSelect").value;

                    if (!targetNoteId || targetNoteId === currentNoteId) return alert("Select a valid note to link.");

                    try {
                        const res = await fetch("/note-links", {
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

                        if (!res.ok) {
                            const j = await res.json().catch(() => ({}));
                            throw new Error(j.message || "Failed to add link");
                        }

                        await refreshLinkedNotes(currentNoteId);
                    } catch (err) {
                        console.error("Failed to add link", err);
                        alert("Failed to add link.");
                    }
                });
            }

            // Remove attachment (from edit modal)
            if (removeAttachmentBtn) {
                removeAttachmentBtn.addEventListener("click", async () => {
                    const noteId = document.getElementById("editNoteId").value;
                    if (!noteId) return;
                    if (!confirm("Remove this attachment?")) return;

                    try {
                        const res = await fetch(`/notes/${noteId}/remove-attachment`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({ _method: "POST" })
                        });

                        if (res.ok) {
                            // hide container and clear file input
                            if (currentAttachmentContainer) currentAttachmentContainer.classList.add("hidden");
                            if (currentAttachmentPreview) {
                                currentAttachmentPreview.src = "";
                                currentAttachmentPreview.classList.add("hidden");
                            }
                            if (currentAttachmentLink) {
                                currentAttachmentLink.href = "#";
                                currentAttachmentLink.classList.add("hidden");
                            }
                            if (editAttachmentInput) editAttachmentInput.value = "";
                            await refreshLinkedNotes(document.getElementById("editNoteId").value);
                            alert("Attachment removed.");
                        } else {
                            const j = await res.json().catch(() => ({}));
                            throw new Error(j.message || "Failed to remove attachment");
                        }
                    } catch (err) {
                        console.error("Error removing attachment", err);
                        alert("Error removing attachment.");
                    }
                });
            }

            // Edit form submit (update note)
            if (editForm) {
                editForm.addEventListener("submit", async (e) => {
                    e.preventDefault();
                    const id = document.getElementById("editNoteId").value;
                    if (!id) return alert("No note selected.");

                    const formData = new FormData();
                    formData.append("title", document.getElementById("editTitle").value || "");
                    formData.append("content", document.getElementById("editContent").value || "");
                    formData.append("color", document.getElementById("editColor").value || "");
                    // append attachment only if a file is chosen
                    const file = editAttachmentInput.files && editAttachmentInput.files[0];
                    if (file) formData.append("attachment", file);

                    // include CSRF token in header (FormData will set content-type automatically)
                    try {
                        const res = await fetch(`/notes/${id}/update`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: formData
                        });

                        if (res.ok) {
                            alert("Note updated successfully!");
                            modal.classList.add("hidden");
                            // refresh page or update UI as needed
                            window.location.reload();
                        } else {
                            const j = await res.json().catch(() => null);
                            console.error("Update failed:", j);
                            alert(j?.message || "Failed to update note.");
                        }
                    } catch (err) {
                        console.error("Update failed", err);
                        alert("Network error while updating note.");
                    }
                });
            }

            // Ensure linked notes are refreshed when modal closes or opens as needed (optional safety)
            // (you already call refreshLinkedNotes on editNote, so this is just a guard)
        });
    </script>
</div>