<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Song extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'artist',
        'album',
        'genre',
        'year',
        'duration',
        'file_path',
        'file_size',
        'cover_path',
        'play_count',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year' => 'integer',
        'duration' => 'integer',
        'file_size' => 'integer',
        'play_count' => 'integer',
        'is_public' => 'boolean',
    ];

    /**
     * Get the user that owns the song.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The playlists that contain this song.
     */
    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class)
            ->withPivot('order', 'added_at')
            ->orderBy('order');
    }

    /**
     * Get the play histories for the song.
     */
    public function playHistories(): HasMany
    {
        return $this->hasMany(PlayHistory::class);
    }

    /**
     * Get formatted duration (mm:ss).
     */
    public function getFormattedDurationAttribute(): string
    {
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Increment play count.
     */
    public function incrementPlayCount(): void
    {
        $this->increment('play_count');
    }

    /**
     * Scope: Get user's songs only.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Search songs.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('artist', 'like', "%{$search}%")
                ->orWhere('album', 'like', "%{$search}%");
        });
    }

    /**
     * Scope: Filter by genre.
     */
    public function scopeByGenre($query, $genre)
    {
        return $query->where('genre', $genre);
    }

    /**
     * Scope: Most played songs.
     */
    public function scopeMostPlayed($query, $limit = 10)
    {
        return $query->orderBy('play_count', 'desc')->limit($limit);
    }

    /**
     * Scope: Recently added.
     */
    public function scopeRecentlyAdded($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Scope: Get public songs.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Get cover image URL.
     */
    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover_path)
            return null;
        if (str_starts_with($this->cover_path, 'http'))
            return $this->cover_path;
        return \Illuminate\Support\Facades\Storage::url($this->cover_path);
    }

    /**
     * Get stream URL (Local or External).
     */
    public function getStreamUrlAttribute(): string
    {
        if ($this->file_path && str_starts_with($this->file_path, 'http'))
            return $this->file_path;
        return route('songs.stream', $this->id);
    }
}
