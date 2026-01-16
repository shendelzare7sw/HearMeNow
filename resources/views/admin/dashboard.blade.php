@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Admin Dashboard</h1>
                <p class="text-gray-400">Manage your platform</p>
            </div>
        </div>

        <!-- Platform Stats -->
        <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-amber-600 to-orange-700 rounded-lg p-6">
                <p class="text-sm text-orange-100 mb-1">Total Users</p>
                <p class="text-4xl font-bold text-white">{{ $stats['total_users'] ?? 0 }}</p>
            </div>
            <div class="bg-gradient-to-br from-orange-600 to-red-700 rounded-lg p-6">
                <p class="text-sm text-orange-100 mb-1">Total Songs</p>
                <p class="text-4xl font-bold text-white">{{ $stats['total_songs'] ?? 0 }}</p>
            </div>
            <div class="bg-gradient-to-br from-lime-600 to-green-700 rounded-lg p-6">
                <p class="text-sm text-green-100 mb-1">Storage Used</p>
                <p class="text-4xl font-bold text-white">{{ formatBytes($stats['total_storage_used'] ?? 0) }}</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-amber-600 rounded-lg p-6">
                <p class="text-sm text-yellow-100 mb-1">Avg Songs/User</p>
                <p class="text-4xl font-bold text-white">{{ $stats['average_songs_per_user'] ?? 0 }}</p>
            </div>
        </section>

        <!-- Quick Actions -->
        <section class="flex gap-4">
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-2 px-4 py-2 bg-app-gray hover:bg-app-light-gray text-white rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
                Manage Users
            </a>
        </section>

        <!-- Recent Users -->
        <section class="bg-app-gray rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h2 class="text-lg font-semibold text-white">Recent Users</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-black/30">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Storage</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($recentUsers ?? [] as $user)
                            <tr class="hover:bg-white/5">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        @if($user->avatar_url)
                                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover border border-gray-600">
                                        @else
                                            <div class="w-8 h-8 rounded-full gradient-bg flex items-center justify-center text-black font-semibold text-sm">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="text-white">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-400">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $user->formatted_storage_used }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="text-brand-orange hover:text-white transition-colors">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No users registered yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Important Notes -->
        <section class="bg-app-gray rounded-lg p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Admin Permissions</h2>
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div class="p-4 bg-green-900/30 border border-green-800 rounded-lg">
                    <p class="font-semibold text-green-400 mb-2">✓ Can Do:</p>
                    <ul class="text-gray-300 space-y-1">
                        <li>• View and manage registered users</li>
                        <li>• Adjust user storage limits</li>
                        <li>• View platform statistics</li>
                        <li>• Delete user accounts (ToS violations)</li>
                    </ul>
                </div>
                <div class="p-4 bg-red-900/30 border border-red-800 rounded-lg">
                    <p class="font-semibold text-red-400 mb-2">✗ Cannot Do:</p>
                    <ul class="text-gray-300 space-y-1">
                        <li>• View/listen to user's private songs</li>
                        <li>• Access user's playlists</li>
                        <li>• Delete user songs (privacy)</li>
                        <li>• View user's listening history</li>
                    </ul>
                </div>
            </div>
        </section>
    </div>

    @php
        function formatBytes($bytes)
        {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
            $bytes /= pow(1024, $pow);
            return round($bytes, 2) . ' ' . $units[$pow];
        }
    @endphp
@endsection