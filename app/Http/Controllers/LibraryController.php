<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    /**
     * Display the library dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Get user's songs with pagination
        $songs = $user->songs()->latest()->paginate(20);

        // Get user's playlists
        $playlists = $user->playlists()->withCount('songs')->latest()->get();

        // Get recently played songs
        $recentlyPlayed = $user->songs()
            ->whereHas('playHistories', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->take(10)
            ->get();

        // Calculate statistics
        $totalSongs = $user->songs()->count();
        $totalPlaylists = $playlists->count();
        $totalArtists = $user->songs()->distinct('artist')->count('artist');
        $storageUsed = $this->formatBytes($user->storage_used ?? 0);

        // Get available genres
        $genres = Genre::orderBy('name')->get();

        return view('library.index', compact(
            'songs',
            'playlists',
            'recentlyPlayed',
            'totalSongs',
            'totalPlaylists',
            'totalArtists',
            'storageUsed',
            'genres'
        ));
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Show upload page.
     */
    public function upload()
    {
        $user = auth()->user();
        $genres = Genre::orderBy('name')->get();

        return view('library.upload', [
            'storage_used' => $user->formatted_storage_used,
            'storage_limit' => $user->formatted_storage_limit,
            'storage_percentage' => $user->storage_usage_percentage,
            'remaining_storage' => $user->remaining_storage,
            'genres' => $genres,
        ]);
    }

    /**
     * Search songs.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $genres = Genre::orderBy('name')->get();
        $songs = collect();

        if ($query) {
            $songs = auth()->user()
                ->songs()
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('artist', 'like', "%{$query}%")
                        ->orWhere('album', 'like', "%{$query}%");
                })
                ->get();
        }

        if ($request->wantsJson()) {
            // Append cover_url to each song for JSON response
            $songs->each(function ($song) {
                $song->cover_url = $song->cover_url;
                $song->formatted_duration = $song->formatted_duration;
            });
            return response()->json($songs);
        }

        return view('library.search', compact('songs', 'query', 'genres'));
    }

    /**
     * Filter by genre.
     */
    public function byGenre(Genre $genre)
    {
        $songs = auth()->user()
            ->songs()
            ->where('genre', $genre->name)
            ->orderBy('title')
            ->get();

        return view('library.by-genre', compact('songs', 'genre'));
    }

    /**
     * View all albums.
     */
    public function albums()
    {
        $albums = auth()->user()
            ->songs()
            ->select('album', 'artist')
            ->selectRaw('COUNT(*) as song_count')
            ->selectRaw('MIN(cover_path) as cover_path')
            ->whereNotNull('album')
            ->groupBy('album', 'artist')
            ->orderBy('album')
            ->get();

        return view('library.albums', compact('albums'));
    }

    /**
     * View all artists.
     */
    public function artists()
    {
        $artists = auth()->user()
            ->songs()
            ->select('artist')
            ->selectRaw('COUNT(*) as song_count')
            ->selectRaw('COUNT(DISTINCT album) as album_count')
            ->whereNotNull('artist')
            ->groupBy('artist')
            ->orderBy('artist')
            ->get();

        return view('library.artists', compact('artists'));
    }
}
