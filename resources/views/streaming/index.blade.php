@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="px-4 sm:px-0 pb-12">
        <h1 class="text-3xl font-bold text-white mb-6">Discover</h1>

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-purple-900/50 to-blue-900/50 rounded-xl p-8 border border-white/10 mb-8 relative overflow-hidden group">
            <div class="relative z-10">
                <h2 class="text-2xl font-bold text-white mb-2">Welcome to HearMeNow</h2>
                <p class="text-gray-300 mb-6">Discover music from our community creators.</p>
                <a href="{{ route('stream.browse') }}" class="btn-primary inline-block">Start Exploring</a>
            </div>
            <!-- Decor -->
            <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-black/20 to-transparent"></div>
        </div>

        <!-- Featured Section -->
        <div class="mb-10">
            <h2 class="text-xl font-bold text-white mb-4">Featured Tracks</h2>
            @if($featured->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($featured as $song)
                        <div class="group relative bg-app-gray/30 p-4 rounded-lg hover:bg-app-gray/60 transition-colors">
                            <!-- Cover -->
                            <div class="aspect-square bg-app-dark rounded-md mb-4 overflow-hidden relative shadow-lg">
                                @if($song->cover_path)
                                    <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
                                        <svg class="w-12 h-12 text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
                                    </div>
                                @endif
                                <!-- Play Button Overlay -->
                                <button class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity"
                                        x-data
                                        @click="$dispatch('play-song', { 
                                            id: '{{ $song->id }}', 
                                            title: '{{ addslashes($song->title) }}', 
                                            artist: '{{ addslashes($song->artist ?? 'Unknown Artist') }}', 
                                            stream_url: '{{ $song->stream_url }}', 
                                            cover_url: '{{ $song->cover_url }}',
                                            provider: '{{ $song->provider ?? 'local' }}'
                                        })">
                                    <div class="w-12 h-12 bg-app-orange rounded-full flex items-center justify-center text-black transform hover:scale-105 transition-transform shadow-xl relative">
                                        <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        @if(($song->provider ?? '') === 'spotify')
                                            <span class="absolute -top-2 -right-2 bg-green-500 text-xs text-black font-bold px-1 rounded">30s</span>
                                        @endif
                                    </div>
                                </button>
                            </div>
                            <!-- Meta -->
                            <h3 class="text-white font-semibold truncate">{{ $song->title }}</h3>
                            <p class="text-sm text-gray-400 truncate">{{ $song->artist ?: 'Unknown Artist' }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm">No public songs yet. Be the first to upload!</p>
            @endif
        </div>

        <!-- Latest Section -->
        <div>
            <h2 class="text-xl font-bold text-white mb-4">Fresh Finds</h2>
            @if($latest->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($latest as $song)
                        <div class="group relative bg-app-gray/30 p-4 rounded-lg hover:bg-app-gray/60 transition-colors">
                            <div class="aspect-square bg-app-dark rounded-md mb-4 overflow-hidden relative shadow-lg">
                                @if($song->cover_path)
                                    <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
                                        <svg class="w-12 h-12 text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
                                    </div>
                                @endif
                                <button class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity"
                                        x-data
                                        @click="$dispatch('play-song', { 
                                            id: '{{ $song->id }}', 
                                            title: '{{ addslashes($song->title) }}', 
                                            artist: '{{ addslashes($song->artist ?? 'Unknown Artist') }}', 
                                            stream_url: '{{ $song->stream_url }}', 
                                            cover_url: '{{ $song->cover_url }}' 
                                        })">
                                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-black transform hover:scale-105 transition-transform shadow-lg">
                                        <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </button>
                            </div>
                            <h3 class="text-white font-medium text-sm truncate">{{ $song->title }}</h3>
                            <p class="text-xs text-gray-400 truncate">{{ $song->artist ?: 'Unknown Artist' }}</p>
                            <p class="text-xs text-gray-600 mt-1">Uploaded {{ $song->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection