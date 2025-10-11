<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\StickyNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BoardActivityNotification;
use Illuminate\Support\Facades\Storage;

class StickyNoteController extends Controller
{
    public function index(Board $board)
    {
        $notes = $board->notes()->with('user')->get();
        return view('notes.index', compact('board', 'notes'));
    }

    public function show(Board $board)
    {
        $notes = StickyNote::where('board_id', $board->id)
            ->where('is_archived', false)
            ->get();

        return view('board-tab', compact('board', 'notes'));
    }


    public function create(Board $board)
    {
        return view('notes.create', compact('board'));
    }

    public function store(Request $request, Board $board)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'color' => 'required|in:red,yellow,green',
            'attachment' => 'nullable|file|max:5120', // ✅ 5MB max
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
            'user_id' => auth()->id(),
            'attachment' => $attachmentPath, // ✅ new
        ]);

        // Notify board members
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
            ->with('success', 'Sticky note added successfully with attachment!');
    }


    public function move(Request $request, StickyNote $note)
    {
        $note->update([
            'x' => $request->x,
            'y' => $request->y,
        ]);

        return response()->json(['success' => true]);
    }
    public function archive($id)
    {
        $note = StickyNote::findOrFail($id);

        // ✅ Instead of deleting, we just "soft delete" or mark as archived
        $note->is_archived = true;
        $note->save();

        return response()->json(['message' => 'Note archived successfully.']);
    }

    public function update(Request $request, $id)
    {
        $note = StickyNote::findOrFail($id);

        // validation: title nullable (same as store), content nullable, color restricted to your three options
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'color' => 'required|in:red,yellow,green',
            'attachment' => 'nullable|file|max:5120', // 5 MB
        ]);

        // If a new attachment is uploaded, remove the old one (if any) and store new one
        if ($request->hasFile('attachment')) {
            // delete old file if exists
            if ($note->attachment && Storage::disk('public')->exists($note->attachment)) {
                Storage::disk('public')->delete($note->attachment);
            }

            $path = $request->file('attachment')->store('attachments', 'public');
            $note->attachment = $path;
        }

        // Update fields (keep existing if not provided)
        $note->title = $validated['title'] ?? $note->title;
        $note->content = $validated['content'] ?? $note->content;
        $note->color = $validated['color'] ?? $note->color;
        $note->save();

        return response()->json(['success' => true, 'message' => 'Note updated']);
    }





}
