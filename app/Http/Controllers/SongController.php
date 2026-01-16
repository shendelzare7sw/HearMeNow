<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use getID3;

class SongController extends Controller
{
    /**
     * Display a listing of the user's songs.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->songs();

        // Search
        if ($search = $request->input('search')) {
            $query->search($search);
        }

        // Filter by genre
        if ($genre = $request->input('genre')) {
            $query->byGenre($genre);
        }

        // Sort
        $sortBy = $request->input('sort', 'created_at');
        $sortOrder = $request->input('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $songs = $query->paginate(20);

        return view('library.index', compact('songs'));
    }

    /**
     * Show the form for creating a new song.
     */
    public function create()
    {
        $genres = \App\Models\Genre::all();
        return view('library.upload', compact('genres'));
    }

    /**
     * Store a newly uploaded song.
     */
    public function store(Request $request)
    {
        $request->validate([
            'audio_file' => 'required|file|mimes:mp3,wav,ogg,flac,m4a|max:51200', // 50MB
            'title' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'album' => 'nullable|string|max:255',
            'genre_id' => 'nullable|exists:genres,id',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'cover_art' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = auth()->user();
        $file = $request->file('audio_file');
        $fileSize = $file->getSize();

        // Check storage limit
        if (!$user->hasStorageSpace($fileSize)) {
            return back()->with('error', 'Storage limit exceeded. Please delete some songs or upgrade your plan.');
        }

        // Store the audio file
        $filePath = $file->store("songs/{$user->id}", 'private');

        // Try to extract duration using getID3
        $duration = 0;
        try {
            $getID3 = new getID3();
            $fileInfo = $getID3->analyze($file->getRealPath());
            $duration = isset($fileInfo['playtime_seconds']) ? (int) $fileInfo['playtime_seconds'] : 0;
        } catch (\Exception $e) {
            // Duration extraction failed, keep 0
        }

        // Handle cover art
        $coverPath = null;
        if ($request->hasFile('cover_art')) {
            $coverPath = $request->file('cover_art')->store("covers/{$user->id}", 'public');
        }

        // Create song record
        $song = $user->songs()->create([
            'title' => $request->title,
            'artist' => $request->artist,
            'album' => $request->album,
            'genre_id' => $request->genre_id,
            'year' => $request->year,
            'duration' => $duration,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'cover_path' => $coverPath,
            'is_public' => $request->has('is_public'),
        ]);

        // Update user's storage used
        $user->incrementStorage($fileSize);

        return redirect()->route('library.index')->with('success', 'Song uploaded successfully!');
    }

    /**
     * Display the specified song.
     */
    public function show(Song $song)
    {
        // Check if user owns the song
        if ($song->user_id !== auth()->id()) {
            abort(403);
        }

        return view('library.show', compact('song'));
    }

    /**
     * Show the form for editing the specified song.
     */
    public function edit(Song $song)
    {
        // Check if user owns the song
        if ($song->user_id !== auth()->id()) {
            abort(403);
        }

        return view('library.edit', compact('song'));
    }

    /**
     * Update the specified song metadata.
     */
    public function update(Request $request, Song $song)
    {
        // Check if user owns the song
        if ($song->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'album' => 'nullable|string|max:255',
            'genre_id' => 'nullable|exists:genres,id',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'artist', 'album', 'genre_id', 'year']);
        $data['is_public'] = $request->has('is_public');

        // Handle cover art upload
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($song->cover_path) {
                Storage::disk('public')->delete($song->cover_path);
            }

            $data['cover_path'] = $request->file('cover')->store("covers/{$song->user_id}", 'public');
        }

        $song->update($data);

        return redirect()->route('library.index')->with('success', 'Song updated successfully!');
    }

    /**
     * Remove the specified song from storage.
     */
    public function destroy(Song $song)
    {
        // Check if user owns the song
        if ($song->user_id !== auth()->id()) {
            abort(403);
        }

        $fileSize = $song->file_size;

        // Delete files
        Storage::disk('private')->delete($song->file_path);
        if ($song->cover_path) {
            Storage::disk('public')->delete($song->cover_path);
        }

        // Update user's storage
        auth()->user()->decrementStorage($fileSize);

        // Delete song record
        $song->delete();

        return redirect()->route('library.index')->with('success', 'Song deleted successfully!');
    }

    /**
     * Stream the song file.
     */
    public function stream(Song $song)
    {
        // Check if user owns the song or is public
        if ($song->user_id !== auth()->id() && !$song->is_public) {
            abort(403);
        }

        if (!Storage::disk('private')->exists($song->file_path)) {
            abort(404);
        }

        $path = Storage::disk('private')->path($song->file_path);
        $mimeType = mime_content_type($path) ?: 'audio/mpeg';

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Accept-Ranges' => 'bytes',
        ]);
    }
}
