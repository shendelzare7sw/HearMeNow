@extends('layouts.app')

@section('title', 'User Details - ' . $user->name)

@section('content')
    <div class="space-y-6" x-data="{ showDeleteModal: false }">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white">User Details</h1>
                    <p class="text-gray-400">Viewing {{ $user->name }}'s account</p>
                </div>
            </div>
            <button @click="showDeleteModal = true"
                class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                </svg>
                Delete User
            </button>
        </div>

        <!-- User Info Card -->
        <div class="bg-app-gray rounded-lg p-6">
            <div class="flex items-start gap-6">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}"
                            class="w-24 h-24 rounded-full object-cover">
                    @else
                        <div
                            class="w-24 h-24 rounded-full gradient-bg flex items-center justify-center text-black text-3xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- User Details -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Name</label>
                        <p class="text-lg text-white font-medium">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Email</label>
                        <p class="text-lg text-white font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Joined</label>
                        <p class="text-lg text-white font-medium">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Email Verified</label>
                        <p
                            class="text-lg font-medium {{ $user->email_verified_at ? 'text-green-400' : 'text-yellow-400' }}">
                            {{ $user->email_verified_at ? 'Yes' : 'No' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-app-gray rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Total Songs</p>
                <p class="text-2xl font-bold text-white">{{ $stats['total_songs'] ?? 0 }}</p>
            </div>
            <div class="bg-app-gray rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Playlists</p>
                <p class="text-2xl font-bold text-white">{{ $stats['total_playlists'] ?? 0 }}</p>
            </div>
            <div class="bg-app-gray rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Storage Used</p>
                <p class="text-2xl font-bold text-white">{{ $stats['storage_used'] ?? '0 B' }}</p>
            </div>
            <div class="bg-app-gray rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Storage Usage</p>
                <p class="text-2xl font-bold text-white">{{ $stats['storage_percentage'] ?? 0 }}%</p>
            </div>
        </div>

        <!-- Storage Limit Control -->
        <div class="bg-app-gray rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Storage Settings</h3>
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Current Storage Limit</label>
                    <div class="flex items-center gap-4">
                        <select name="storage_limit"
                            class="w-full bg-app-darker border border-gray-700 text-white rounded-lg py-2 px-4 focus:border-brand-orange focus:ring-brand-orange">
                            <option value="536870912" {{ $user->storage_limit == 536870912 ? 'selected' : '' }}>512 MB
                            </option>
                            <option value="1073741824" {{ $user->storage_limit == 1073741824 ? 'selected' : '' }}>1 GB
                            </option>
                            <option value="2147483648" {{ $user->storage_limit == 2147483648 ? 'selected' : '' }}>2 GB
                            </option>
                            <option value="5368709120" {{ $user->storage_limit == 5368709120 ? 'selected' : '' }}>5 GB
                            </option>
                            <option value="10737418240" {{ $user->storage_limit == 10737418240 ? 'selected' : '' }}>10 GB
                            </option>
                        </select>
                        <button type="submit" class="btn-primary whitespace-nowrap">
                            Update Limit
                        </button>
                    </div>
                </div>

                <!-- Storage Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-400 mb-1">
                        <span>{{ $user->formatted_storage_used }}</span>
                        <span>{{ $user->formatted_storage_limit }}</span>
                    </div>
                    <div class="w-full h-2 bg-gray-700 rounded-full">
                        <div class="h-full gradient-bg rounded-full transition-all"
                            style="width: {{ min($user->storage_usage_percentage, 100) }}%"></div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Privacy Notice -->
        <div class="bg-yellow-900/30 border border-yellow-800 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                </svg>
                <div>
                    <p class="font-semibold text-yellow-400">Privacy Notice</p>
                    <p class="text-sm text-gray-300">As admin, you cannot view or listen to this user's songs and playlists.
                        This is to protect user privacy.</p>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70"
            @keydown.escape.window="showDeleteModal = false" style="display: none;">
            <div class="bg-app-gray rounded-lg shadow-2xl max-w-md w-full mx-4 p-6" @click.away="showDeleteModal = false">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-red-600 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Delete User Account</h3>
                    <p class="text-gray-400 mb-2">Are you sure you want to delete <strong
                            class="text-white">{{ $user->name }}</strong>?</p>
                    <p class="text-red-400 text-sm mb-6">This will permanently delete all their songs, playlists, and data.
                        This action cannot be undone.</p>
                    <div class="flex gap-3">
                        <button type="button" @click="showDeleteModal = false"
                            class="flex-1 py-2 px-4 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-full transition-colors">
                            Cancel
                        </button>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full py-2 px-4 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-full transition-colors">
                                Yes, Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection