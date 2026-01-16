<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayHistory extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'song_id',
        'played_at',
        'completed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'played_at' => 'datetime',
        'completed' => 'boolean',
    ];

    /**
     * Get the user that played the song.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the song that was played.
     */
    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }

    /**
     * Scope: Get user's play history.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Recently played.
     */
    public function scopeRecentlyPlayed($query, $limit = 20)
    {
        return $query->orderBy('played_at', 'desc')->limit($limit);
    }

    /**
     * Scope: Completed plays only.
     */
    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }
}
