<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StreamController extends Controller
{
    /**
     * Display the streaming home page.
     */
    public function index(\App\Services\SpotifyService $spotify)
    {
        $localFeatured = \App\Models\Song::public()
            ->with('user')
            ->mostPlayed(10)
            ->get();

        $spotifyTrending = $spotify->getTrending(10);
        $featured = $localFeatured->merge($spotifyTrending);

        $latest = \App\Models\Song::public()
            ->with('user')
            ->recentlyAdded(10)
            ->get();

        return view('streaming.index', compact('featured', 'latest'));
    }

    /**
     * Browse / Search Public Songs.
     */
    public function browse(\Illuminate\Http\Request $request, \App\Services\SpotifyService $spotify)
    {
        $genres = \App\Models\Genre::orderBy('name')->get();
        $songs = null;

        if ($request->has('q')) {
            $localSongs = \App\Models\Song::public()
                ->with('user')
                ->search($request->q)
                ->get();

            $spotifySongs = $spotify->search($request->q);

            $songs = $localSongs->merge($spotifySongs);
        }

        return view('streaming.browse', compact('genres', 'songs'));
    }

    /**
     * Public Playlists.
     */
    public function playlists()
    {
        return view('streaming.playlists');
    }
}
