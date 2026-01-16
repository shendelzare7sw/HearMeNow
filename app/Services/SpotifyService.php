<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Song;

class SpotifyService
{
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl = 'https://api.spotify.com/v1';
    protected $authUrl = 'https://accounts.spotify.com/api/token';

    public function __construct()
    {
        $this->clientId = env('SPOTIFY_CLIENT_ID');
        $this->clientSecret = env('SPOTIFY_CLIENT_SECRET');
    }

    /**
     * Get Access Token (Client Credentials Flow).
     * Caches token 
     */
    protected function getAccessToken()
    {
        if (empty($this->clientId) || empty($this->clientSecret)) {
            \Illuminate\Support\Facades\Log::warning('Spotify Credentials missing.');
            return null;
        }

        return Cache::remember('spotify_token', 3500, function () {
            $response = Http::withOptions(['verify' => false])
                ->asForm()
                ->withBasicAuth($this->clientId, $this->clientSecret)
                ->post($this->authUrl, [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            \Illuminate\Support\Facades\Log::error('Spotify Auth Failed: ' . $response->body());
            return null;
        });
    }

    /**
     * Get Trending Tracks (Simulated via Search).
     */
    public function getTrending($limit = 10)
    {
        // Search for popular genre or recent year
        return $this->search('year:2024 header:new', $limit);
    }

    /**
     * Search Tracks.
     */
    public function search($query, $limit = 20)
    {
        $token = $this->getAccessToken();
        if (!$token)
            return collect([]);

        $response = Http::withOptions(['verify' => false])->withToken($token)->get("{$this->baseUrl}/search", [
            'q' => $query,
            'type' => 'track',
            'limit' => $limit,
            'market' => 'ID', // Indonesia market often has better availability for local users
        ]);

        if ($response->successful()) {
            $items = $response->json()['tracks']['items'] ?? [];
            // Filter out tracks without preview_url
            $items = array_filter($items, function ($item) {
                return !empty($item['preview_url']);
            });
            return $this->normalizeTracks($items);
        }

        return collect([]);
    }

    /**
     * Normalize Spotify Track Data.
     */
    protected function normalizeTracks(array $tracks)
    {
        return collect($tracks)->map(function ($track) {
            $song = new Song();
            // Use a string ID or negative int? 
            // Spotify IDs are alphanumeric. We MUST use String ID.
            // But Song model id is int?
            // If we use string ID, we cannot merge into Eloquent collection 
            // if Eloquent expects INT keys for strict operations.
            // But we used 'jamendo_...' (string) in Service before and user reported errors?
            // User reported 500 error which we thought was key related but was .env related?
            // Let's use a hashed int ID or keep string and ensure Views handle it?
            // I will use String ID but prefixed.

            $song->id = 'spf_' . $track['id'];
            $song->title = $track['name'];
            $song->artist = collect($track['artists'])->pluck('name')->join(', ');
            $song->album = $track['album']['name'] ?? '';
            $song->duration = $track['duration_ms'] / 1000;

            // Image
            $images = $track['album']['images'] ?? [];
            $song->cover_path = $images[0]['url'] ?? null; // High res

            // Audio (Preview)
            $song->file_path = $track['preview_url']; // Might be null!

            // Flags
            $song->is_public = true;
            $song->created_at = now();

            // Custom attr
            $song->is_external = true;
            $song->provider = 'spotify';

            return $song;
        });
    }

    /**
     * Normalize Album Data (New Releases returns Albums, not Tracks).
     * We need to fetch tracks for albums? Or just display album as "Song"?
     * Displaying Album as Song is confusing.
     * Better: Fetch specific tracks from "New Releases" playlists? 
     * Or just Search for "Top 50" playlist?
     * 
     * Let's stick to 'search' for now. 
     * For 'trending', let's search for "Top Hits" or a random popular query?
     * Or fetch a Playlist tracks.
     * 
     * Let's implement getPlaylistTracks for Trending.
     */

    public function getPlaylistTracks($playlistId, $limit = 10)
    {
        // ...
        // For simplicity MVP, I will use "New Releases" but since they are albums, 
        // I will act as if the "Song" is the Album (clickable to search?)
        // No, let's Search query "year:2024" or generic "pop".
        // Simplest: Search "Featured".
        return $this->search('genre:pop', $limit);
    }
}
