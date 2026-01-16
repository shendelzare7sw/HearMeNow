@extends('layouts.app')

@section('title', 'Your Library')

@section('content')
    <div class="space-y-6 sm:space-y-8 px-0">
        <!-- Welcome Section -->
        <section>
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">
                @php
                    $hour = now()->hour;
                    $greeting = match (true) {
                        $hour < 12 => 'Good morning',
                        $hour < 18 => 'Good afternoon',
                        default => 'Good evening'
                    };
                @endphp
                {{ $greeting }}, {{ auth()->user()->name }}
            </h1>
            <p class="text-gray-400 text-sm sm:text-base">Here's what you've been listening to</p>
        </section>

        <!-- Quick Stats -->
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="bg-gradient-to-br from-amber-600 to-orange-700 rounded-lg p-3 sm:p-4">
                <p class="text-xs sm:text-sm text-orange-100">Total Songs</p>
                <p class="text-2xl sm:text-3xl font-bold text-white">{{ $totalSongs ?? 0 }}</p>
            </div>
            <div class="bg-gradient-to-br from-orange-600 to-red-700 rounded-lg p-3 sm:p-4">
                <p class="text-xs sm:text-sm text-orange-100">Playlists</p>
                <p class="text-2xl sm:text-3xl font-bold text-white">{{ $totalPlaylists ?? 0 }}</p>
            </div>
            <div class="bg-gradient-to-br from-lime-600 to-green-700 rounded-lg p-3 sm:p-4">
                <p class="text-xs sm:text-sm text-green-100">Artists</p>
                <p class="text-2xl sm:text-3xl font-bold text-white">{{ $totalArtists ?? 0 }}</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-amber-600 rounded-lg p-3 sm:p-4">
                <p class="text-xs sm:text-sm text-yellow-100">Storage Used</p>
                <p class="text-2xl sm:text-3xl font-bold text-white">{{ $storageUsed ?? '0 B' }}</p>
            </div>
        </section>

        <!-- Your Songs -->
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg sm:text-xl font-bold text-white">Your Songs</h2>
                <a href="{{ route('library.upload') }}" class="btn-primary text-xs sm:text-sm py-2 px-3 sm:px-4">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4a1 1 0 011 1v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H5a1 1 0 110-2h6V5a1 1 0 011-1z" />
                    </svg>
                    Upload
                </a>
            </div>

            @if(isset($songs) && $songs->count() > 0)
                <!-- Song List -->
                <div class="bg-app-gray/50 rounded-lg overflow-hidden">
                    <!-- Header (Desktop only) -->
                    <div
                        class="hidden sm:grid grid-cols-12 gap-4 px-4 py-3 text-xs sm:text-sm text-gray-500 border-b border-gray-700">
                        <div class="col-span-1">#</div>
                        <div class="col-span-5">Title</div>
                        <div class="col-span-3">Album</div>
                        <div class="col-span-2">Date Added</div>
                        <div class="col-span-1 text-right">Duration</div>
                    </div>

                    <!-- Songs -->
                    @foreach($songs as $index => $song)
                            <div class="flex sm:grid sm:grid-cols-12 gap-2 sm:gap-4 px-3 sm:px-4 py-3 sm:py-2 hover:bg-white/10 transition-colors group cursor-pointer"
                                x-data @click="$store.player.playQueue({{ json_encode($songs->map(fn($s) => [
                            'id' => $s->id,
                            'title' => $s->title,
                            'artist' => $s->artist,
                            'cover_url' => $s->cover_url ?? '/images/default-album-cover.png',
                            'stream_url' => route('songs.stream', $s)
                        ])) }}, {{ $index }})">

                                <!-- Number (Desktop) -->
                                <div class="hidden sm:flex col-span-1 items-center">
                                    <span class="text-gray-500 group-hover:hidden text-sm">{{ $index + 1 }}</span>
                                    <svg class="w-4 h-4 text-white hidden group-hover:block" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5.14v14l11-7-11-7z" />
                                    </svg>
                                </div>

                                <!-- Title & Artist -->
                                <div class="flex items-center gap-3 flex-1 sm:col-span-5 min-w-0">
                                    <img src="{{ $song->cover_url ?? '/images/default-album-cover.png' }}" alt="{{ $song->title }}"
                                        class="w-10 sm:w-10 h-10 sm:h-10 rounded object-cover flex-shrink-0"
                                        onerror="this.src='/images/default-album-cover.png'">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-white truncate text-sm sm:text-base">{{ $song->title }}</p>
                                        <p class="text-gray-500 truncate text-xs sm:text-sm">{{ $song->artist ?? 'Unknown Artist' }}</p>
                                    </div>
                                </div>

                                <!-- Album (Desktop) -->
                                <div class="hidden sm:flex col-span-2 items-center text-gray-500 text-sm truncate">
                                    {{ $song->album ?? '-' }}
                                </div>

                                <!-- Date (Desktop) -->
                                <div class="hidden sm:flex col-span-2 items-center text-gray-500 text-sm">
                                    {{ $song->created_at->diffForHumans() }}
                                </div>

                                <!-- Actions & Duration -->
                                <div class="flex items-center justify-end gap-3 sm:col-span-2">
                                    <a href="{{ route('songs.edit', $song) }}"
                                        class="p-1.5 text-gray-400 hover:text-white rounded-full hover:bg-white/10" @click.stop
                                        title="Edit Song">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <span class="text-gray-500 text-xs sm:text-sm">
                                        {{ $song->formatted_duration ?? '0:00' }}
                                    </span>
                                </div>
                            </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(method_exists($songs, 'links'))
                    <div class="mt-6">
                        {{ $songs->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12 sm:py-16 bg-app-gray/30 rounded-lg">
                    <div
                        class="w-14 sm:w-16 h-14 sm:h-16 mx-auto gradient-bg rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-7 sm:w-8 h-7 sm:h-8 text-black" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-white mb-2">No songs yet</h3>
                    <p class="text-gray-500 mb-6 text-sm sm:text-base">Upload your first song to get started</p>
                    <a href="{{ route('library.upload') }}" class="btn-primary">
                        Upload Music
                    </a>
                </div>
            @endif
        </section>

        <!-- Your Playlists -->
        @if(isset($playlists) && $playlists->count() > 0)
            <section>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg sm:text-xl font-bold text-white">Your Playlists</h2>
                    <a href="{{ route('playlists.index') }}" class="text-sm text-gray-400 hover:text-white">See all</a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">
                    @foreach($playlists->take(6) as $playlist)
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
                            <p class="text-xs sm:text-sm text-gray-500">{{ $playlist->songs_count ?? 0 }} songs</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection