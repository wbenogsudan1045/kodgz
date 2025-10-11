<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\StickyNote;
use Illuminate\Http\Request;

class StickyNoteController extends Controller
{
    public function index(Board $board)
    {
        $notes = $board->notes()->with('user')->get();
        return view('notes.index', compact('board', 'notes'));
    }

    public function show(Board $board, StickyNote $note)
    {
        return view('notes.show', compact('board', 'note'));
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
        ]);

        $board->stickyNotes()->create([
            'title' => $request->title,
            'content' => $request->content,
            'color' => $request->color,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('boards.show', $board->id)->with('success', 'Sticky note added!');
    }

    public function move(Request $request, StickyNote $note)
    {
        $request->validate([
            'x' => 'required|integer',
            'y' => 'required|integer',
        ]);

        $note->update([
            'x' => $request->x,
            'y' => $request->y,
        ]);

        return response()->json(['success' => true]);
    }

}
