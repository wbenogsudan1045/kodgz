<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show(Request $request)
    {
        $user = $request->user();

        // Assuming you have Board and StickyNote models with appropriate relationships:
        $boardsMade = \App\Models\Board::where('user_id', $user->id)->count();
        $boardsEntered = $user->boards()->count(); // if you have many-to-many
        $notes = \App\Models\StickyNote::where('user_id', $user->id)->get();

        $totalNotes = $notes->count();
        $greenNotes = $notes->where('color', 'green')->count();
        $redNotes = $notes->where('color', 'red')->count();
        $yellowNotes = $notes->where('color', 'yellow')->count();

        return view('profile.show', compact(
            'user',
            'boardsMade',
            'boardsEntered',
            'totalNotes',
            'greenNotes',
            'redNotes',
            'yellowNotes'
        ));
    }

}
