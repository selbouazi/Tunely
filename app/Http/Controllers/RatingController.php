<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\PendingComment;
use App\Models\Rating;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RatingController extends Controller
{
    public function store(Request $request, Instrument $instrument): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $existing = Rating::where('user_id', auth()->id())
            ->where('instrument_id', $instrument->id)
            ->first();

        if ($existing) {
            return back()->withErrors(['rating' => 'Ya has valorado este instrumento']);
        }

        $rating = Rating::create([
            'user_id' => auth()->id(),
            'instrument_id' => $instrument->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        PendingComment::where('user_id', auth()->id())
            ->where('instrument_id', $instrument->id)
            ->where('has_commented', false)
            ->update(['has_commented' => true]);

        return back()->with('success', 'Valoración enviada correctamente');
    }

    public function adminIndex(): Response
    {
        $ratings = Rating::with('user', 'instrument')->orderBy('created_at', 'desc')->get();

        return Inertia::render('Admin/Ratings/Index', [
            'ratings' => $ratings,
        ]);
    }

    public function destroy(Rating $rating): RedirectResponse
    {
        $rating->delete();

        return redirect()->route('admin.opiniones.index')->with('success', 'Valoración eliminada');
    }
}
