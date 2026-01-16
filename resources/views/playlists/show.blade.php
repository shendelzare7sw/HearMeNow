@extends('layouts.app')

@section('title', $playlist->name)

@section('content')
    <div class="px-4 sm:px-0">
        <!-- Playlist Header -->
        <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6 mb-8">
            <!-- Cover -->
            <div class="w-40 sm:w-56 h-40 sm:h-56 flex-shrink-0 shadow-2xl">
                @if($playlist->cover_url)
                    <img src="{{ $playlist->cover_url }}" alt="{{ $playlist->name }}"
                        class="w-full h-full object-cover rounded-lg">
                @else
                    <div class="w-full h-full gradient-bg rounded-lg flex items-center justify-center">
                        <svg class="w-20 h-20 text-black/60" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M15 6H3v2h12V6zm0 4H3v2h12v-2zM3 16h8v-2H3v2zM17 6v8.18c-.31-.11-.65-.18-1-.18-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3V8h3V6h-5z" />
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="flex-1 text-center sm:text-left">
                <p class="text-sm text-gray-400 uppercase tracking-wider mb-2">Playlist</p>
                <h1 class="text-3xl sm:text-5xl font-bold text-white mb-4">{{ $playlist->name }}</h1>
                @if($playlist->description)
                    <p class="text-gray-400 mb-4">{{ $playlist->description }}</p>
                @endif
                <p class="text-sm text-gray-400">
                    {{ $playlist->songs->count() }} songs
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-4 mb-8">
            @if($playlist->songs->count() > 0)
                    <button
                        class="w-14 h-14 gradient-bg rounded-full flex items-center justify-center hover:scale-105 transition-transform"
                        x-data @click="$store.player.playQueue({{ json_encode($playlist->songs->map(fn($s) => [
                    'id' => $s->id,
                    'title' => $s->title,
                    'artist' => $s->artist,
                    'cover_url' => $s->cover_url ?? '/images/default-album-cover.png',
                    'stream_url' => route('songs.stream', $s)
                ])) }}, 0)">
                        <svg class="w-7 h-7 text-black ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5.14v14l11-7-11-7z" />
                        </svg>
                    </button>
            @endif
            <a href="{{ route('playlists.edit', $playlist) }}" class="p-3 text-gray-400 hover:text-white transition-colors"
                title="Edit">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                </svg>
            </a>
            <div x-data="{ showDeleteModal: false }">
                <button @click="showDeleteModal = true" class="p-3 text-gray-400 hover:text-red-400 transition-colors"
                    title="Delete">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                    </svg>
                </button>
                <!-- Modal -->
                <div x-show="showDeleteModal" style="display: none;"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4" x-transition.opacity>
                    <div class="bg-app-gray rounded-xl max-w-sm w-full p-6 shadow-2xl"
                        @click.away="showDeleteModal = false">
                        <h3 class="text-xl font-bold text-white mb-2">Delete Playlist?</h3>
                        <p class="text-gray-400 mb-6">Are you sure you want to delete "{{ $playlist->name }}"?</p>
                        <div class="flex gap-3">
                            <button @click="showDeleteModal = false" class="flex-1 btn-secondary">Cancel</button>
                            <form action="{{ route('playlists.destroy', $playlist) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded-full transition-colors">Yes,
                                    Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Songs List -->
        @if($playlist->songs->count() > 0)
            <div class="bg-app-gray/50 rounded-lg overflow-hidden">
                <!-- Header (Desktop) -->
                <div class="hidden sm:grid grid-cols-12 gap-4 px-4 py-3 text-sm text-gray-500 border-b border-gray-700">
                    <div class="col-span-1">#</div>
                    <div class="col-span-4">Title</div>
                    <div class="col-span-3">Album</div>
                    <div class="col-span-2">Added</div>
                    <div class="col-span-2 text-right">Duration</div>
                </div>

                <!-- Songs -->
                @foreach($playlist->songs as $index => $song)
                    <div class="flex sm:grid sm:grid-cols-12 gap-2 sm:gap-4 px-4 py-3 hover:bg-white/10 transition-colors group cursor-pointer"
                        x-data @click="$store.player.playQueue({{ json_encode($playlist->songs->map(fn($s) => [
                        'id' => $s->id,
                        'title' => $s->title,
                        'artist' => $s->artist,
                        'cover_url' => $s->cover_url ?? '/images/default-album-cover.png',
                        'stream_url' => route('songs.stream', $s)
                    ])) }}, {{ $index }})">
                        <!-- Number -->
                        <div class="hidden sm:flex col-span-1 items-center">
                            <span class="text-gray-500 group-hover:hidden text-sm">{{ $index + 1 }}</span>
                            <svg class="w-4 h-4 text-white hidden group-hover:block" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5.14v14l11-7-11-7z" />
                            </svg>
                        </div>

                        <!-- Title & Artist -->
                        <div class="flex items-center gap-3 flex-1 sm:col-span-4 min-w-0">
                            <img src="{{ $song->cover_url ?? '/images/default-album-cover.png' }}" alt="{{ $song->title }}"
                                class="w-10 h-10 rounded object-cover flex-shrink-0"
                                onerror="this.src='/images/default-album-cover.png'">
                            <div class="min-w-0 flex-1">
                                <p class="text-white truncate text-sm sm:text-base">{{ $song->title }}</p>
                                <p class="text-gray-500 text-xs sm:text-sm truncate">{{ $song->artist ?? 'Unknown Artist' }}</p>
                            </div>
                        </div>

                        <!-- Album (Desktop) -->
                        <div class="hidden sm:flex col-span-3 items-center text-gray-500 text-sm truncate">
                            {{ $song->album ?? '-' }}
                        </div>

                        <!-- Added (Desktop) -->
                        <div class="hidden sm:flex col-span-2 items-center text-gray-500 text-sm">
                            {{ $song->pivot->added_at ? \Carbon\Carbon::parse($song->pivot->added_at)->diffForHumans() : '' }}
                        </div>

                        <!-- Duration & Actions -->
                        <div class="flex items-center justify-end gap-3 sm:col-span-2">
                            <span class="text-gray-500 text-sm">{{ $song->formatted_duration ?? '0:00' }}</span>

                            <form action="{{ route('playlists.remove-song', [$playlist, $song]) }}" method="POST"
                                class="flex items-center" @click.stop>
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 text-gray-500 hover:text-red-400 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity"
                                    title="Remove from playlist">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 bg-app-gray/30 rounded-lg">
                <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                </svg>
                <h3 class="text-xl font-semibold text-white mb-2">This playlist is empty</h3>
                <p class="text-gray-500">Add songs from your library to this playlist</p>
            </div>
        @endif
    </div>

    <!-- Add Songs Section -->
    <div class="mt-12 bg-app-gray/30 rounded-xl p-6 mb-24" x-data="songSearch()">
        <h3 class="text-xl font-bold text-white mb-4">Add Songs to Playlist</h3>
        <p class="text-gray-400 text-sm mb-4">Search for songs in your library to add them here.</p>

        <div class="relative max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" x-model="query" @input.debounce.300ms="search" placeholder="Search for songs..."
                class="w-full bg-app-darker border-none rounded-full py-2 pl-10 pr-4 text-white focus:ring-2 focus:ring-brand-orange placeholder-gray-500">
        </div>

        <div class="mt-4 space-y-2">
            <template x-if="loading">
                <div class="text-gray-400 text-sm py-2">Searching...</div>
            </template>

            <template x-for="song in results" :key="song.id">
                <div
                    class="flex items-center justify-between p-3 bg-app-darker/50 rounded-lg hover:bg-app-darker transition">
                    <div class="flex items-center gap-3">
                        <img :src="song.cover_url || '/images/default-album-cover.png'"
                            class="w-10 h-10 rounded object-cover" onerror="this.src='/images/default-album-cover.png'">
                        <div>
                            <p class="text-white font-medium text-sm" x-text="song.title"></p>
                            <p class="text-gray-400 text-xs" x-text="song.artist || 'Unknown Artist'"></p>
                        </div>
                    </div>

                    <form action="{{ route('playlists.add-song', $playlist) }}" method="POST">
                        @csrf
                        <input type="hidden" name="song_id" :value="song.id">
                        <button type="submit"
                            class="text-xs sm:text-sm border border-gray-600 text-white px-3 py-1 rounded-full hover:border-white transition hover:bg-white hover:text-black font-medium">
                            Add
                        </button>
                    </form>
                </div>
            </template>

            <div x-show="query.length > 1 && results.length === 0 && !loading" class="text-gray-400 text-sm py-2">
                No songs found matching your query.
            </div>
        </div>
    </div>

    <script>
        function songSearch() {
            return {
                query: '',
                results: [],
                loading: false,
                async search() {
                    if (this.query.length < 2) {
                        this.results = [];
                        return;
                    }
                    this.loading = true;
                    try {
                        const res = await axios.get('{{ route('library.search') }}', { params: { q: this.query } });
                        this.results = res.data;
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
@endsection