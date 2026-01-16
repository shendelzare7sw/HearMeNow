@extends('layouts.app')

@section('title', 'Your Playlists')

@section('content')
    <div class="px-4 sm:px-0">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Your Playlists</h1>
                <p class="text-gray-400">{{ $playlists->count() }} playlists</p>
            </div>
            <a href="{{ route('playlists.create') }}" class="btn-primary inline-flex items-center gap-2 justify-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4a1 1 0 011 1v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H5a1 1 0 110-2h6V5a1 1 0 011-1z" />
                </svg>
                Create Playlist
            </a>
        </div>

        @if($playlists->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @foreach($playlists as $playlist)
                    <a href="{{ route('playlists.show', $playlist) }}" class="playlist-card p-3 sm:p-4 group">
                        <div class="relative mb-3 sm:mb-4">
                            @if($playlist->cover_url)
                                <img src="{{ $playlist->cover_url }}" alt="{{ $playlist->name }}"
                                    class="w-full aspect-square object-cover rounded-md shadow-lg">
                            @else
                                <div class="w-full aspect-square gradient-bg rounded-md shadow-lg flex items-center justify-center">
                                    <svg class="w-10 sm:w-12 h-10 sm:h-12 text-black/60" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M15 6H3v2h12V6zm0 4H3v2h12v-2zM3 16h8v-2H3v2zM17 6v8.18c-.31-.11-.65-.18-1-.18-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3V8h3V6h-5z" />
                                    </svg>
                                </div>
                            @endif
                            <button
                                class="play-button absolute bottom-2 right-2 w-10 sm:w-12 h-10 sm:h-12 gradient-bg rounded-full flex items-center justify-center shadow-lg hover:scale-105 transition-transform">
                                <svg class="w-5 sm:w-6 h-5 sm:h-6 text-black ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5.14v14l11-7-11-7z" />
                                </svg>
                            </button>
                        </div>
                        <h3 class="font-semibold text-white truncate text-sm sm:text-base">{{ $playlist->name }}</h3>
                        <p class="text-xs sm:text-sm text-gray-500">{{ $playlist->songs_count ?? $playlist->songs()->count() }}
                            songs</p>
                    </a>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-16 h-16 mx-auto gradient-bg rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-black" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M15 6H3v2h12V6zm0 4H3v2h12v-2zM3 16h8v-2H3v2zM17 6v8.18c-.31-.11-.65-.18-1-.18-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3V8h3V6h-5z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">No playlists yet</h3>
                <p class="text-gray-500 mb-6">Create your first playlist to organize your music</p>
                <a href="{{ route('playlists.create') }}" class="btn-primary">
                    Create Playlist
                </a>
            </div>
        @endif
    </div>
@endsection