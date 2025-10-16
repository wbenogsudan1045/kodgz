<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoteLinkController;
use App\Http\Controllers\StickyNoteController;
use App\Http\Controllers\NotificationController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// =====================================
// ✅ Authenticated & Verified User Routes
// =====================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard (Board listing)
    Route::get('/dashboard', [BoardController::class, 'index'])->name('dashboard');

    // =====================================
    // ✅ Board Routes — Specific before generic
    // =====================================

    // Search Boards (must be before /boards/{board})
    Route::get('/boards/search', [BoardController::class, 'search'])->name('boards.search');

    // Join Board
    Route::post('/boards/{board}/join', [BoardController::class, 'join'])->name('boards.join');

    // Verify Board Password
    Route::post('/boards/{board}/verify-password', [BoardController::class, 'verifyPassword'])
        ->name('boards.verify-password');

    // Board Resource (except index, since handled by dashboard)
    Route::resource('boards', BoardController::class)->except(['index']);

    // =====================================
    // ✅ Sticky Notes
    // =====================================
    Route::post('/notes/{note}/move', [StickyNoteController::class, 'move'])->name('notes.move');
    Route::get('boards/{board}/notes', [StickyNoteController::class, 'index'])->name('boards.notes.index');
    Route::post('boards/{board}/notes', [StickyNoteController::class, 'store'])->name('boards.notes.store');
    Route::get('boards/{board}/notes/{note}', [StickyNoteController::class, 'show'])->name('boards.notes.show');
    Route::post('/notes/{id}/archive', [StickyNoteController::class, 'archive'])->name('notes.archive');
    Route::post('/notes/{id}/update', [StickyNoteController::class, 'update']);
    Route::get('/notes/{id}/linked', [NoteLinkController::class, 'fetchLinkedNotes']);
    Route::post('/note-links', [NoteLinkController::class, 'store']);
    // Fetch all notes for a board (for dropdown)
    Route::get('/boards/{board}/notes-list', function (App\Models\Board $board) {
        return response()->json([
            'notes' => $board->stickyNotes()->where('is_archived', false)->select('id', 'title')->get(),
        ]);
    });

    // Unlink notes (you need this method, see controller below)
    Route::post('/note-links/unlink', [App\Http\Controllers\NoteLinkController::class, 'unlinkNotes']);





    // =====================================
    // ✅ Profile
    // =====================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
});

// =====================================
// ✅ Notifications
// =====================================
Route::get('/notifications', function () {
    $notifications = auth()->user()->notifications()->take(20)->get();
    return view('notifications.index', compact('notifications'));
})->middleware('auth')->name('notifications.index');
Route::get('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])
    ->name('notifications.read');

// =====================================
// ✅ Testing
// =====================================
Route::get('/test', function () {
    return 'Routing works!';
});

// =====================================
// ✅ Auth Routes (Login, Register, etc.)
// =====================================
require __DIR__ . '/auth.php';
