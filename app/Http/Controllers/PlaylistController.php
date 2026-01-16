<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the user's playlists.
     */
    public function index()
    {
        $playlists = auth()->user()->playlists()->withCount('songs')->get();

        return view('playlists.index', compact('playlists'));
    }

    /**
     * Show the form for creating a new playlist.
     */
    public function create()
    {
        return view('playlists.create');
    }

    /**
     * Store a newly created playlist.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'description']);

        // Handle cover art
        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store("covers/" . auth()->id(), 'public');
        }

        $playlist = auth()->user()->playlists()->create($data);

        return redirect()->route('playlists.show', $playlist)
            ->with('success', 'Playlist created successfully!');
    }

    /**
     * Display the specified playlist.
     */
    public function show(Playlist $playlist)
    {
        // Check if user owns the playlist
        if ($playlist->user_id !== auth()->id()) {
            abort(403);
        }

        $playlist->load('songs');

        return view('playlists.show', compact('playlist'));
    }

    /**
     * Show the form for editing the specified playlist.
     */
    public function edit(Playlist $playlist)
    {
        // Check if user owns the playlist
        if ($playlist->user_id !== auth()->id()) {
            abort(403);
        }

        return view('playlists.edit', compact('playlist'));
    }

    /**
     * Update the specified playlist.
     */
    public function update(Request $request, Playlist $playlist)
    {
        // Check if user owns the playlist
        if ($playlist->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'description']);

        // Handle cover art
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($playlist->cover_path) {
                Storage::disk('public')->delete($playlist->cover_path);
            }

            $data['cover_path'] = $request->file('cover')->store("covers/{$playlist->user_id}", 'public');
        }

        $playlist->update($data);

        return redirect()->route('playlists.show', $playlist)
            ->with('success', 'Playlist updated successfully!');
    }

    /**
     * Remove the specified playlist.
     */
    public function destroy(Playlist $playlist)
    {
        // Check if user owns the playlist
        if ($playlist->user_id !== auth()->id()) {
            abort(403);
        }

        // Don't allow deleting favorites playlist
        if ($playlist->is_favorite) {
            return back()->with('error', 'Cannot delete favorites playlist.');
        }

        // Delete cover if exists
        if ($playlist->cover_path) {
            Storage::disk('public')->delete($playlist->cover_path);
        }

        $playlist->delete();

        return redirect()->route('playlists.index')
            ->with('success', 'Playlist deleted successfully!');
    }

    /**
     * Add a song to the playlist.
     */
    public function addSong(Request $request, Playlist $playlist)
    {
        // Check if user owns the playlist
        if ($playlist->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'song_id' => 'required|exists:songs,id',
        ]);

        $song = Song::findOrFail($request->song_id);

        // Check if user owns the song
        if ($song->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if song is already in playlist
        if ($playlist->songs()->where('song_id', $song->id)->exists()) {
            return back()->with('info', 'Song is already in this playlist.');
        }

        $playlist->addSong($song);

        return back()->with('success', 'Song added to playlist!');
    }

    /**
     * Remove a song from the playlist.
     */
    public function removeSong(Playlist $playlist, Song $song)
    {
        // Check if user owns the playlist
        if ($playlist->user_id !== auth()->id()) {
            abort(403);
        }

        $playlist->removeSong($song);

        return back()->with('success', 'Song removed from playlist!');
    }

    /**
     * Reorder songs in the playlist.
     */
    public function reorder(Request $request, Playlist $playlist)
    {
        // Check if user owns the playlist
        if ($playlist->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'song_ids' => 'required|array',
            'song_ids.*' => 'exists:songs,id',
        ]);

        $playlist->reorderSongs($request->song_ids);

        return response()->json(['success' => true]);
    }
}
