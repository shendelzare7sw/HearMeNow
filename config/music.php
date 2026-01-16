<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Allowed Audio Formats
    |--------------------------------------------------------------------------
    |
    | List of allowed audio file formats for upload.
    |
    */

    'allowed_formats' => [
        'mp3',
        'wav',
        'ogg',
        'flac',
        'm4a',
    ],

    /*
    |--------------------------------------------------------------------------
    | Max File Size (in KB)
    |--------------------------------------------------------------------------
    |
    | Maximum file size for audio uploads in kilobytes.
    | Default: 10MB (10240 KB)
    |
    */

    'max_file_size' => env('MAX_AUDIO_FILE_SIZE', 10240),

    /*
    |--------------------------------------------------------------------------
    | Default Storage Limit (in bytes)
    |--------------------------------------------------------------------------
    |
    | Default storage limit for new users in bytes.
    | Default: 5GB (5368709120 bytes)
    |
    */

    'default_storage_limit' => env('DEFAULT_STORAGE_LIMIT', 5368709120),

    /*
    |--------------------------------------------------------------------------
    | Cover Art Settings
    |--------------------------------------------------------------------------
    */

    'cover_art' => [
        'max_size' => 2048, // KB
        'allowed_formats' => ['jpeg', 'png', 'jpg', 'gif', 'webp'],
        'default_cover' => '/images/default-album-cover.png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Player Settings
    |--------------------------------------------------------------------------
    */

    'player' => [
        'default_volume' => 0.7,
        'crossfade_duration' => 3, // seconds
        'buffer_size' => 1024, // KB
    ],

];
