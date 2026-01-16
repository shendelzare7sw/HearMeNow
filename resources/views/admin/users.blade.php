@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Manage Users</h1>
                <p class="text-gray-400">{{ $users->total() }} registered users</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Users Table -->
        <div class="bg-app-gray rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-black/30">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Songs</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Storage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Joined</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-white/5">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full gradient-bg flex items-center justify-center text-black font-semibold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="text-white font-medium">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-400">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $user->songs_count ?? 0 }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-400">{{ $user->formatted_storage_used }} /
                                        {{ $user->formatted_storage_limit }}</div>
                                    <div class="w-24 h-1 bg-gray-700 rounded-full mt-1">
                                        <div class="h-full gradient-bg rounded-full"
                                            style="width: {{ min($user->storage_usage_percentage, 100) }}%"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-400">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="text-brand-orange hover:text-white transition-colors">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No users registered yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection