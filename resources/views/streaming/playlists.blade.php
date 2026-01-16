@extends('layouts.app')

@section('title', 'Public Playlists')

@section('content')
    <div class="px-4 sm:px-0">
        <h1 class="text-3xl font-bold text-white mb-6">Playlists</h1>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            <!-- Placeholders -->
            @for($i = 0; $i < 5; $i++)
                <div class="group relative">
                    <div class="aspect-square bg-app-gray rounded-md mb-4 overflow-hidden relative">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-purple-800 to-blue-800 opacity-50 group-hover:opacity-70 transition-opacity">
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white/50" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M15 6H3v2h12V6zm0 4H3v2h12v-2zM3 16h8v-2H3v2zM17 6v8.18c-.31-.11-.65-.18-1-.18-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3V8h3V6h-5z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-white font-bold truncate">Top Hits {{ date('Y') }}</h3>
                    <p class="text-sm text-gray-400 truncate">Curated by HearMeNow</p>
                </div>
            @endfor
        </div>
    </div>
@endsection