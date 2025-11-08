<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\StickyNote;
use App\Models\NoteLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BoardActivityNotification;
use Illuminate\Support\Facades\Storage;

class StickyNoteController extends Controller
{
    // ===============================
    // 📋 List notes for a board (used by route: GET boards/{board}/notes)
    // ===============================
    public function index(Board $board)
    {
        $notes = StickyNote::where('board_id', $board->id)
            ->where('is_archived', false)
            ->get();

        // Return JSON for API endpoints used by frontend (dropdowns etc.)
        // If you prefer to return a view here, change accordingly.
        return response()->json([
            'notes' => $notes
        ]);
    }

    // ===============================
    // 🧾 Show single note (boards/{board}/notes/{note})
    // ===============================
    public function show(Board $board, StickyNote $note)
    {
        // Ensure the note belongs to the board
        if ($note->board_id !== $board->id) {
            return response()->json(['error' => 'Note not found on this board.'], 404);
        }

        return response()->json([
            'note' => $note
        ]);
    }

    // ===============================
    // 🧱 Create Note
    // ===============================
    public function store(Request $request, Board $board)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'color' => 'required|in:red,yellow,green',
            'attachment' => 'nullable|file|max:5120', // 5MB
            'linked_note_id' => 'nullable|exists:sticky_notes,id', // optional link
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        // Create note
        $note = $board->stickyNotes()->create([
            'title' => $request->title,
            'content' => $request->content,
            'color' => $request->color,
            'user_id' => Auth::id(),
            'attachment' => $attachmentPath,
        ]);

        // Optionally create a link to an existing note on same board (if provided)
        if ($request->filled('linked_note_id')) {
            $linkedId = $request->input('linked_note_id');
            // Prevent linking to self and ensure the linked note exists
            if ($linkedId != $note->id) {
                NoteLink::firstOrCreate([
                    'note_a_id' => $note->id,
                    'note_b_id' => $linkedId,
                ]);
            }
        }

        // Notify other board members
        $members = $board->users()->where('users.id', '!=', Auth::id())->get();
        if ($members->count() > 0) {
            Notification::send(
                $members,
                new BoardActivityNotification(
                    $board,
                    Auth::user()->name . ' added a new note: ' . ($note->title ?? 'Untitled')
                )
            );
        }

        return redirect()
            ->route('boards.show', $board->id)
            ->with('success', 'Sticky note added successfully!');
    }

    // ===============================
// ✏️ Update (Edit) Note
// ===============================
    public function update(Request $request, $id)
    {
        $note = StickyNote::findOrFail($id);
        $board = $note->board;

        // Authorization check
        if (Auth::id() !== $note->user_id && Auth::id() !== $board->user_id) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'color' => 'required|in:red,yellow,green',
            'attachment' => 'nullable|file|max:5120',
        ]);

        // Replace old attachment if new one is uploaded
        if ($request->hasFile('attachment')) {
            if ($note->attachment && Storage::disk('public')->exists($note->attachment)) {
                Storage::disk('public')->delete($note->attachment);
            }
            $note->attachment = $request->file('attachment')->store('attachments', 'public');
            $note->save();
        }

        // Update the note fields
        $note->update([
            'title' => $validated['title'] ?? $note->title,
            'content' => $validated['content'] ?? $note->content,
            'color' => $validated['color'] ?? $note->color,
        ]);

        // Notify other board members
        $members = $board->users()->where('users.id', '!=', Auth::id())->get();
        if ($members->count() > 0) {
            Notification::send(
                $members,
                new BoardActivityNotification(
                    $board,
                    Auth::user()->name . ' edited a note: ' . ($note->title ?? 'Untitled')
                )
            );
        }

        return response()->json(['success' => true, 'message' => 'Note updated successfully']);
    }

    // ===============================
// 🗂️ Archive (Soft Delete) Note
// ===============================
    public function archive($id)
    {
        $note = StickyNote::findOrFail($id);
        $board = $note->board;

        // Authorization check
        if (Auth::id() !== $note->user_id && Auth::id() !== $board->user_id) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $note->is_archived = true;
        $note->save();

        $members = $board->users()->where('users.id', '!=', Auth::id())->get();
        if ($members->count() > 0) {
            Notification::send(
                $members,
                new BoardActivityNotification(
                    $board,
                    Auth::user()->name . ' archived a note: ' . ($note->title ?? 'Untitled')
                )
            );
        }

        return response()->json(['message' => 'Note archived successfully.']);
    }

    // ===============================
    // 📦 Move Note (drag)
    // ===============================
    public function move(Request $request, StickyNote $note)
    {
        $note->update([
            'x' => $request->x,
            'y' => $request->y,
        ]);

        return response()->json(['success' => true]);
    }
    public function removeAttachment($id)
    {
        $note = StickyNote::findOrFail($id);

        if ($note->attachment && Storage::exists($note->attachment)) {
            Storage::delete($note->attachment);
        }

        $note->attachment = null;
        $note->save();

        return response()->json(['message' => 'Attachment removed.']);
    }

}
