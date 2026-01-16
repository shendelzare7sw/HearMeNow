<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Auth routes (handled by Laravel Breeze)
require __DIR__ . '/auth.php';

// Protected routes - require authentication
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard / Library (Redirect root dashboard to My Music or keep as local landing?)
    // We will establish 'Stream' as the main entry point later, for now just add routes.

    // Streaming (New Public Area)
    Route::prefix('stream')->name('stream.')->group(function () {
        Route::get('/', [\App\Http\Controllers\StreamController::class, 'index'])->name('home');
        Route::get('/browse', [\App\Http\Controllers\StreamController::class, 'browse'])->name('browse');
        Route::get('/playlists', [\App\Http\Controllers\StreamController::class, 'playlists'])->name('playlists');
    });

    // Dashboard / Local Library
    Route::get('/library', [\App\Http\Controllers\LibraryController::class, 'index'])->name('library.index');
    Route::get('/library/upload', [LibraryController::class, 'upload'])->name('library.upload');
    Route::get('/library/search', [LibraryController::class, 'search'])->name('library.search');

    // Songs
    Route::resource('songs', SongController::class)->except(['index', 'show']);
    Route::get('/songs/{song}/stream', [SongController::class, 'stream'])->name('songs.stream');

    // Playlists
    Route::resource('playlists', PlaylistController::class);
    Route::post('/playlists/{playlist}/songs', [PlaylistController::class, 'addSong'])->name('playlists.add-song');
    Route::delete('/playlists/{playlist}/songs/{song}', [PlaylistController::class, 'removeSong'])->name('playlists.remove-song');

    // Player
    Route::post('/player/{song}/play', [PlayerController::class, 'logPlay'])->name('player.log-play');
    Route::get('/player/history', [PlayerController::class, 'history'])->name('player.history');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'verified', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users.index');
    Route::get('/users/{user}', [AdminDashboardController::class, 'showUser'])->name('users.show');
    Route::patch('/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'deleteUser'])->name('users.destroy');
});
