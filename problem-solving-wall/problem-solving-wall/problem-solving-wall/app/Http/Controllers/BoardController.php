<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;

class BoardController extends Controller
{
    // Show all boards
    public function index()
    {
        $boards = Board::all();
        return view('dashboard', compact('boards'));
    }

    // Show a single board
    public function show(Board $board)
    {
        return view('boards.show', compact('board'));
    }
}
