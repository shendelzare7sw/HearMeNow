@extends('layouts.app')

@section('title', 'Browse')

@section('content')
    <div class="px-4 sm:px-0 pb-12">
        <!-- Search Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-6">Browse</h1>
            <form action="{{ route('stream.browse') }}" method="GET" class="relative max-w-2xl">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        d="M10.533 1.279c-5.18 0-9.407 4.14-9.407 9.279s4.226 9.279 9.407 9.279c2.234 0 4.29-.77 5.907-2.058l4.353 4.353a1 1 0 101.414-1.414l-4.344-4.344a9.157 9.157 0 002.077-5.816c0-5.14-4.226-9.28-9.407-9.28z" />
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="What do you want to listen to?"
                    class="w-full bg-white/10 border border-transparent focus:border-white/20 focus:bg-white/15 text-white placeholder-gray-400 rounded-full py-3.5 pl-12 pr-4 focus:outline-none transition-all">
            </form>
        </div>

        @if(request()->has('q'))
            <!-- Search Results -->
            <div class="mb-10">
                <h2 class="text-xl font-bold text-white mb-4">Results for "{{ request('q') }}"</h2>
                @if(isset($songs) && $songs->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        @foreach($songs as $song)
                            <div class="group relative bg-app-gray/30 p-4 rounded-lg hover:bg-app-gray/60 transition-colors">
                                <div class="aspect-square bg-app-dark rounded-md mb-4 overflow-hidden relative shadow-lg">
                                    @if($song->cover_path)
                                        <img src="{{ Storage::url($song->cover_path) }}" alt="{{ $song->title }}"
                                            class="w-full h-full object-cover transition-transform group-hover:scale-105">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
                                            <svg class="w-12 h-12 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <button
                                        class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity"
                                        x-data @click="$dispatch('play-song', { 
                                                                                                                                id: '{{ $song->id }}', 
                                                                                                                                title: '{{ addslashes($song->title) }}', 
                                                                                                                                artist: '{{ addslashes($song->artist ?? 'Unknown Artist') }}', 
                                                                                                                                stream_url: '{{ $song->stream_url }}', 
                                                                                                    cover_url: '{{ $song->cover_url }}' 
                                                                                                })">
                                        <div
                                            class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-black transform hover:scale-105 transition-transform shadow-lg">
                                            <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                                <h3 class="text-white font-medium text-sm truncate">{{ $song->title }}</h3>
                                <p class="text-xs text-gray-400 truncate">{{ $song->artist ?: 'Unknown Artist' }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $songs->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-400">No songs found matching your search.</p>
                    </div>
                @endif
            </div>
        @else
            <!-- Genres / Categories -->
            <div>
                <h2 class="text-xl font-bold text-white mb-4">Browse all</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @php
                        $colors = ['bg-red-600', 'bg-blue-600', 'bg-green-600', 'bg-yellow-600', 'bg-purple-600', 'bg-pink-600', 'bg-indigo-600', 'bg-orange-600'];
                    @endphp

                    @foreach($genres as $index => $genre)
                        <a href="{{ route('stream.home', ['genre' => $genre->id]) }}"
                            class="{{ $colors[$index % count($colors)] }} rounded-lg p-4 h-32 relative overflow-hidden transition-transform hover:scale-105 group">
                            <span class="text-xl font-bold text-white">{{ $genre->name }}</span>
                            <div class="absolute -bottom-2 -right-4 rotate-[25deg] shadow-lg">
                                <div class="w-16 h-16 bg-black/20 rounded-lg"></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection