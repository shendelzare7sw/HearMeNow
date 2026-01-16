<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Playlist extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'cover_path',
        'is_favorite',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_favorite' => 'boolean',
    ];

    /**
     * Get the user that owns the playlist.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The songs in this playlist.
     */
    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class)
            ->withPivot('order', 'added_at')
            ->orderBy('order');
    }

    /**
     * Get total duration of all songs in playlist.
     */
    public function getTotalDurationAttribute(): int
    {
        return $this->songs->sum('duration');
    }

    /**
     * Get formatted total duration.
     */
    public function getFormattedTotalDurationAttribute(): string
    {
        $totalSeconds = $this->total_duration;
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Get song count.
     */
    public function getSongCountAttribute(): int
    {
        return $this->songs()->count();
    }

    /**
     * Scope: Get user's playlists only.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Get favorites playlist.
     */
    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    /**
     * Add song to playlist.
     */
    public function addSong(Song $song): void
    {
        $maxOrder = $this->songs()->max('order') ?? 0;

        $this->songs()->attach($song->id, [
            'order' => $maxOrder + 1,
            'added_at' => now(),
        ]);
    }

    /**
     * Remove song from playlist.
     */
    public function removeSong(Song $song): void
    {
        $this->songs()->detach($song->id);
    }

    /**
     * Reorder songs in playlist.
     */
    public function reorderSongs(array $songIds): void
    {
        foreach ($songIds as $index => $songId) {
            $this->songs()->updateExistingPivot($songId, [
                'order' => $index + 1,
            ]);
        }
    }
    /**
     * Get cover image URL.
     */
    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_path
            ? \Illuminate\Support\Facades\Storage::url($this->cover_path)
            : null;
    }
}
