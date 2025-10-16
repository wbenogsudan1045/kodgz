<?php

namespace App\Http\Controllers;

use App\Models\StickyNote;
use App\Models\NoteLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteLinkController extends Controller
{
    // ===============================
    // 🔗 Create a link between two notes (POST /note-links)
    // ===============================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'note_a_id' => 'required|exists:sticky_notes,id',
            'note_b_id' => 'required|exists:sticky_notes,id',
            'relation_type' => 'nullable|string|max:50',
        ]);

        if ($validated['note_a_id'] === $validated['note_b_id']) {
            return response()->json(['error' => 'A note cannot link to itself.'], 400);
        }

        $noteA = StickyNote::find($validated['note_a_id']);
        $noteB = StickyNote::find($validated['note_b_id']);

        if (!$noteA || !$noteB) {
            return response()->json(['error' => 'Note(s) not found.'], 404);
        }

        if ($noteA->board_id !== $noteB->board_id) {
            return response()->json(['error' => 'Cannot link notes from different boards.'], 400);
        }

        // Create or update link with relation_type
        $link = NoteLink::updateOrCreate(
            [
                'note_a_id' => $validated['note_a_id'],
                'note_b_id' => $validated['note_b_id'],
            ],
            [
                'relation_type' => $validated['relation_type'] ?? null,
            ]
        );

        return response()->json([
            'message' => 'Notes linked successfully.',
            'link' => $link,
        ]);
    }

    // ===============================
    // 🔍 Fetch linked notes for a note id (GET /notes/{id}/linked)
    // ===============================
    public function fetchLinkedNotes($id)
    {
        $links = NoteLink::where('note_a_id', $id)
            ->orWhere('note_b_id', $id)
            ->with(['noteA:id,title', 'noteB:id,title'])
            ->get()
            ->map(function ($link) use ($id) {
                $otherNote = $link->note_a_id == $id ? $link->noteB : $link->noteA;
                return [
                    'id' => $otherNote->id,
                    'title' => $otherNote->title ?? 'Untitled',
                    'relation_type' => $link->relation_type ?? 'linked',
                ];
            });

        return response()->json(['links' => $links]);
    }

    // ===============================
    // ❌ Unlink notes (POST /note-links/unlink)
    // ===============================
    public function unlinkNotes(Request $request)
    {
        $validated = $request->validate([
            'note_a_id' => 'required|exists:sticky_notes,id',
            'note_b_id' => 'required|exists:sticky_notes,id',
        ]);

        NoteLink::where(function ($q) use ($validated) {
            $q->where('note_a_id', $validated['note_a_id'])
                ->where('note_b_id', $validated['note_b_id']);
        })->orWhere(function ($q) use ($validated) {
            $q->where('note_a_id', $validated['note_b_id'])
                ->where('note_b_id', $validated['note_a_id']);
        })->delete();

        return response()->json(['message' => 'Unlinked']);
    }
}
