<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Song;

class JamendoService
{
    protected $clientId;
    protected $baseUrl = 'https://api.jamendo.com/v3.0';

    public function __construct()
    {
        $this->clientId = env('JAMENDO_CLIENT_ID', '709fa152');
    }

    /**
     * Get trending/popular tracks.
     */
    public function getTrending($limit = 10)
    {
        $response = Http::withOptions(['verify' => false])->get("{$this->baseUrl}/tracks/", [
            'client_id' => $this->clientId,
            'format' => 'json',
            'limit' => $limit,
            // 'boost' => 'popularity_month', 
            // 'include' => 'musicinfo',
            // 'imagesize' => 600,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $results = $data['results'] ?? [];
            return $this->normalizeTracks($results);
        }

        return collect([]);
    }

    /**
     * Search tracks.
     */
    public function search($query, $limit = 20)
    {
        $response = Http::withOptions(['verify' => false])->get("{$this->baseUrl}/tracks/", [
            'client_id' => $this->clientId,
            'format' => 'json',
            'limit' => $limit,
            'search' => $query,
            'imagesize' => 600,
            'include' => 'musicinfo',
        ]);

        if ($response->successful()) {
            return $this->normalizeTracks($response->json()['results'] ?? []);
        }

        return collect([]);
    }

    /**
     * Get tracks by genre (tag).
     */
    public function getByGenre($genre, $limit = 10)
    {
        $response = Http::withOptions(['verify' => false])->get("{$this->baseUrl}/tracks/", [
            'client_id' => $this->clientId,
            'format' => 'json',
            'limit' => $limit,
            'tags' => $genre,
            'imagesize' => 600,
            'boost' => 'popularity_month',
        ]);

        if ($response->successful()) {
            return $this->normalizeTracks($response->json()['results'] ?? []);
        }

        return collect([]);
    }

    /**
     * Normalize Jamendo data to match Song model structure.
     */
    protected function normalizeTracks(array $tracks)
    {
        return collect($tracks)->map(function ($track) {
            // Create a temporary Song object (not saved to DB)
            $song = new Song();
            $song->id = -1 * abs($track['id']); // Negative ID to avoid collision and keep int type
            $song->title = $track['name'];
            $song->artist = $track['artist_name'];
            $song->album = $track['album_name'];
            $song->duration = $track['duration'];
            $song->cover_path = $track['image']; // This is a full URL, we need to handle this in View
            $song->file_path = $track['audio']; // This is a full URL
            $song->is_public = true;
            $song->created_at = \Carbon\Carbon::parse($track['releasedate'] ?? now());

            // Custom attribute to indicate external source
            $song->is_external = true;

            return $song;
        });
    }
}
