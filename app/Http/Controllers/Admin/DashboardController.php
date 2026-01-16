<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Song;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index()
    {
        // Platform statistics
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_songs' => Song::count(),
            'total_storage_used' => User::sum('storage_used'),
            'average_songs_per_user' => round(Song::count() / max(User::count(), 1), 2),
        ];

        // Recent users
        $recentUsers = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top users by storage
        $topStorageUsers = User::where('role', 'user')
            ->orderBy('storage_used', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'topStorageUsers'));
    }

    /**
     * List all users.
     */
    public function users()
    {
        $users = User::where('role', 'user')
            ->withCount('songs')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * View specific user details.
     */
    public function showUser(User $user)
    {
        $user->load(['songs', 'playlists']);

        $stats = [
            'total_songs' => $user->songs()->count(),
            'total_playlists' => $user->playlists()->count(),
            'storage_used' => $user->formatted_storage_used,
            'storage_percentage' => $user->storage_usage_percentage,
        ];

        return view('admin.user-detail', compact('user', 'stats'));
    }

    /**
     * Update user details (storage limit).
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'storage_limit' => 'nullable|integer|min:0',
        ]);

        if ($request->has('storage_limit')) {
            $user->storage_limit = $request->storage_limit;
            $user->save();
        }

        return back()->with('success', 'User updated successfully!');
    }

    /**
     * Suspend/Unsuspend user.
     */
    public function toggleUserStatus(User $user)
    {
        // This would require adding an 'is_active' column to users table
        // For now, just a placeholder

        return back()->with('info', 'User status toggle not yet implemented.');
    }

    /**
     * Delete user account.
     */
    public function deleteUser(User $user)
    {
        // Don't allow deleting admin users
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete admin users.');
        }

        // Delete all user's songs and associated files (cascade will handle DB)
        foreach ($user->songs as $song) {
            \Storage::disk('private')->delete($song->file_path);
            if ($song->cover_path) {
                \Storage::disk('public')->delete($song->cover_path);
            }
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully!');
    }
}
