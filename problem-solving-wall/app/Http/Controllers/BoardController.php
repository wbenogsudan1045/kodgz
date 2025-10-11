<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    // Show boards user has joined
    public function index()
    {
        $boards = Auth::user()->boards; // only joined boards
        return view('dashboard', compact('boards'));
    }

    // Show single board (only if joined)
    public function show(Board $board)
    {
        if (!$board->users->contains(Auth::id())) {
            abort(403, 'You are not a member of this board.');
        }

        // Only load notes that are NOT archived
        $board->load([
            'stickyNotes' => function ($query) {
                $query->where('is_archived', false);
            }
        ]);

        return view('boards.show', [
            'board' => $board,
            'notes' => $board->stickyNotes
        ]);
    }


    // Show create form
    public function create()
    {
        return view('boards.create');
    }

    // Store a new board
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'password' => 'nullable|string|max:255'
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('boards', 'public');
        }

        $validated['user_id'] = auth()->id();

        $board = Board::create($validated);

        // creator auto-joins
        $board->users()->attach(Auth::id());

        return redirect()->route('dashboard')->with('success', 'Board created successfully!');
    }

    // Search boards
    public function search(Request $request)
    {
        $query = $request->input('q');

        $boards = \App\Models\Board::where('name', 'like', "%{$query}%")->get();

        return view('boards.search', compact('boards', 'query'));
    }



    // Join a board
    public function join(Request $request, Board $board)
    {
        $user = Auth::user();

        if (!$board->users()->where('users.id', $user->id)->exists()) {
            $board->users()->attach($user->id);
        }

        return redirect()->route('boards.show', $board->id)
            ->with('success', 'You have joined this board!');
    }


    // Verify password for already joined boards (AJAX)
    public function verifyPassword(Request $request, Board $board)
    {
        // Check if user is already a member
        if (!$board->users->contains(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'You are not a member of this board.'
            ], 403);
        }

        // If board has no password, allow access
        if (!$board->password) {
            return response()->json(['success' => true]);
        }

        // Verify password
        $request->validate([
            'password' => 'required|string'
        ]);

        if ($board->password !== $request->password) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password'
            ], 401);
        }

        return response()->json(['success' => true]);
    }




}