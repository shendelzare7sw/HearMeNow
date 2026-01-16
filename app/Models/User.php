<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'storage_used',
        'storage_limit',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'storage_used' => 'integer',
            'storage_limit' => 'integer',
        ];
    }

    /**
     * Get the songs for the user.
     */
    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }

    /**
     * Get the playlists for the user.
     */
    public function playlists(): HasMany
    {
        return $this->hasMany(Playlist::class);
    }

    /**
     * Get the play histories for the user.
     */
    public function playHistories(): HasMany
    {
        return $this->hasMany(PlayHistory::class);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get is_admin attribute for easy access in templates.
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user has storage space available.
     */
    public function hasStorageSpace(int $additionalSize): bool
    {
        return ($this->storage_used + $additionalSize) <= $this->storage_limit;
    }

    /**
     * Get remaining storage space.
     */
    public function getRemainingStorageAttribute(): int
    {
        return max(0, $this->storage_limit - $this->storage_used);
    }

    /**
     * Get formatted storage used.
     */
    public function getFormattedStorageUsedAttribute(): string
    {
        return $this->formatBytes($this->storage_used);
    }

    /**
     * Get formatted storage limit.
     */
    public function getFormattedStorageLimitAttribute(): string
    {
        return $this->formatBytes($this->storage_limit);
    }

    /**
     * Get storage usage percentage.
     */
    public function getStorageUsagePercentageAttribute(): float
    {
        if ($this->storage_limit == 0) {
            return 0;
        }

        return round(($this->storage_used / $this->storage_limit) * 100, 2);
    }

    /**
     * Increment storage used.
     */
    public function incrementStorage(int $size): void
    {
        $this->increment('storage_used', $size);
    }

    /**
     * Decrement storage used.
     */
    public function decrementStorage(int $size): void
    {
        $this->decrement('storage_used', $size);
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $bytes;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Get favorites playlist for user.
     */
    public function getFavoritesPlaylist(): ?Playlist
    {
        return $this->playlists()->where('is_favorite', true)->first();
    }

    /**
     * Get or create favorites playlist.
     */
    public function getOrCreateFavoritesPlaylist(): Playlist
    {
        $playlist = $this->getFavoritesPlaylist();

        if (!$playlist) {
            $playlist = $this->playlists()->create([
                'name' => 'Favorites',
                'description' => 'Your favorite songs',
                'is_favorite' => true,
            ]);
        }

        return $playlist;
    }
    /**
     * Get avatar URL.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        return \Illuminate\Support\Facades\Storage::url($this->avatar);
    }
}
