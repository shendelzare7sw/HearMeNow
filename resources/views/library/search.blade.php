@extends('layouts.app')

@section('title', 'Search')

@section('content')
    <div class="px-4 sm:px-0">
        @if(isset($query) && $query)
            <!-- Search Results -->
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6">
                Results for "{{ $query }}"
            </h1>

            @if(isset($songs) && $songs->count() > 0)
                <section class="mb-8">
                    <h2 class="text-xl font-semibold text-white mb-4">Songs</h2>
                    <div class="bg-app-gray/50 rounded-lg overflow-hidden">
                        @foreach($songs as $index => $song)
                            <div class="flex items-center gap-4 p-3 hover:bg-white/10 transition-colors cursor-pointer group" x-data
                                @click="$store.player.playQueue({{ json_encode($songs->map(fn($s) => [
                                'id' => $s->id,
                                'title' => $s->title,
                                'artist' => $s->artist,
                                'cover_url' => $s->cover_url ?? '/images/default-album-cover.png',
                                'stream_url' => route('songs.stream', $s)
                            ])) }}, {{ $index }})">
                                <img src="{{ $song->cover_url ?? '/images/default-album-cover.png' }}" alt="{{ $song->title }}"
                                    class="w-12 h-12 rounded object-cover" onerror="this.src='/images/default-album-cover.png'">
                                <div class="flex-1 min-w-0">
                                    <p class="text-white font-medium truncate">{{ $song->title }}</p>
                                    <p class="text-gray-400 text-sm truncate">{{ $song->artist ?? 'Unknown Artist' }}</p>
                                </div>
                                <button
                                    class="opacity-0 group-hover:opacity-100 w-10 h-10 gradient-bg rounded-full flex items-center justify-center transition-opacity">
                                    <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5.14v14l11-7-11-7z" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </section>
            @else
                <div class="text-center py-16">
                    <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-white mb-2">No results found</h3>
                    <p class="text-gray-500">Try searching for something else</p>
                </div>
            @endif
        @else
            <!-- Browse Section -->
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6">Search</h1>

            <!-- Search Input (Mobile) -->
            <div class="sm:hidden mb-6">
                <form action="{{ route('library.search') }}" method="GET" class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M10.533 1.279c-5.18 0-9.407 4.14-9.407 9.279s4.226 9.279 9.407 9.279c2.234 0 4.29-.77 5.907-2.058l4.353 4.353a1 1 0 101.414-1.414l-4.344-4.344a9.157 9.157 0 002.077-5.816c0-5.14-4.226-9.28-9.407-9.28z" />
                    </svg>
                    <input type="text" name="q" placeholder="What do you want to listen to?"
                        class="w-full pl-10 pr-4 py-3 bg-white rounded-full text-black text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-orange">
                </form>
            </div>

            <!-- Browse Genres -->
            <section>
                <h2 class="text-xl font-semibold text-white mb-4">Browse by Genre</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($genres ?? [] as $genre)
                        <a href="{{ route('library.search', ['genre' => $genre->slug ?? $genre->id]) }}"
                            class="relative overflow-hidden rounded-lg h-24 sm:h-32 group"
                            style="background-color: {{ $genre->color ?? '#' . substr(md5($genre->name), 0, 6) }}">
                            <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-colors"></div>
                            <span class="absolute bottom-3 left-3 text-white font-bold text-lg sm:text-xl">{{ $genre->name }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection