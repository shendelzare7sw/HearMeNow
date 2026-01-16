<?php

namespace App\Http\Controllers;

use App\Models\PlayHistory;
use App\Models\Song;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Log a song play in history.
     */
    public function logPlay(Request $request, Song $song)
    {
        // Check if user owns the song
        if ($song->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'completed' => 'boolean',
        ]);

        // Create play history record
        PlayHistory::create([
            'user_id' => auth()->id(),
            'song_id' => $song->id,
            'played_at' => now(),
            'completed' => $request->input('completed', false),
        ]);

        // Increment play count if completed
        if ($request->input('completed', false)) {
            $song->incrementPlayCount();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get recently played songs.
     */
    public function recentlyPlayed()
    {
        $recentlyPlayed = PlayHistory::forUser(auth()->id())
            ->recentlyPlayed(20)
            ->with('song')
            ->get()
            ->pluck('song')
            ->unique('id');

        return view('player.recently-played', compact('recentlyPlayed'));
    }

    /**
     * Get user's queue (for now, just return empty array - can be implemented with sessions).
     */
    public function getQueue()
    {
        $queue = session('player_queue', []);

        return response()->json([
            'queue' => $queue,
        ]);
    }

    /**
     * Update user's queue.
     */
    public function updateQueue(Request $request)
    {
        $request->validate([
            'queue' => 'required|array',
            'queue.*' => 'exists:songs,id',
        ]);

        session(['player_queue' => $request->queue]);

        return response()->json(['success' => true]);
    }

    /**
     * Clear user's queue.
     */
    public function clearQueue()
    {
        session()->forget('player_queue');

        return response()->json(['success' => true]);
    }

    /**
     * Get most played songs.
     */
    public function mostPlayed()
    {
        $mostPlayed = auth()->user()
            ->songs()
            ->mostPlayed(20)
            ->get();

        return view('player.most-played', compact('mostPlayed'));
    }
}
