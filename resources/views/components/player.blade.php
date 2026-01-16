<!-- Fixed Bottom Music Player -->
<div id="music-player" data-turbo-permanent class="fixed bottom-0 left-0 right-0 z-50 glass border-t border-gray-800"
    x-data="{ showQueue: false }" @play-song.window="$store.player.play($event.detail)"
    @play-queue.window="$store.player.playQueue($event.detail.songs, $event.detail.index)">
    <!-- Mobile Player -->
    <div class="sm:hidden">
        <!-- Progress Bar (Mobile - Top) -->
        <div class="h-1 bg-gray-700 w-full cursor-pointer"
            @click="$store.player.seek($event.offsetX / $event.target.offsetWidth)">
            <div class="h-full gradient-bg transition-all" :style="'width: ' + $store.player.progress + '%'"></div>
        </div>

        <!-- Mobile Controls -->
        <div class="px-3 py-2">
            <div class="flex items-center gap-3">
                <!-- Album Art & Info -->
                <template x-if="$store.player.currentSong">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <img :src="$store.player.currentSong.cover_url || '/images/default-album-cover.png'"
                            alt="Album Cover" class="w-12 h-12 rounded object-cover"
                            onerror="this.src='/images/default-album-cover.png'">
                        <div class="min-w-0 flex-1">
                            <p class="text-white text-sm font-medium truncate" x-text="$store.player.currentSong.title">
                            </p>
                            <p class="text-gray-400 text-xs truncate"
                                x-text="$store.player.currentSong.artist || 'Unknown Artist'"></p>
                        </div>
                    </div>
                </template>
                <template x-if="!$store.player.currentSong">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-12 h-12 bg-app-gray rounded flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm">Not playing</p>
                    </div>
                </template>

                <!-- Mobile Control Buttons -->
                <div class="flex items-center gap-2">
                    <!-- Previous -->
                    <button @click="$store.player.prev()" class="p-2 text-gray-400 hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z" />
                        </svg>
                    </button>

                    <!-- Play/Pause -->
                    <button @click="$store.player.toggle()"
                        class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center flex-shrink-0">
                        <template x-if="!$store.player.isPlaying">
                            <svg class="w-5 h-5 text-black ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5.14v14l11-7-11-7z" />
                            </svg>
                        </template>
                        <template x-if="$store.player.isPlaying">
                            <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                            </svg>
                        </template>
                    </button>

                    <!-- Next -->
                    <button @click="$store.player.next()" class="p-2 text-gray-400 hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Player (Full) -->
    <div class="hidden sm:block px-4 py-3">
        <div class="flex items-center justify-between max-w-full">
            <!-- Currently Playing -->
            <div class="flex items-center gap-4 w-72 min-w-0">
                <template x-if="$store.player.currentSong">
                    <div class="flex items-center gap-3 min-w-0">
                        <img :src="$store.player.currentSong.cover_url || '/images/default-album-cover.png'"
                            alt="Album Cover" class="w-14 h-14 rounded object-cover shadow-lg"
                            onerror="this.src='/images/default-album-cover.png'">
                        <div class="min-w-0">
                            <p class="text-white text-sm font-medium truncate" x-text="$store.player.currentSong.title">
                            </p>
                            <p class="text-gray-400 text-xs truncate"
                                x-text="$store.player.currentSong.artist || 'Unknown Artist'"></p>
                        </div>
                    </div>
                </template>
                <template x-if="!$store.player.currentSong">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 bg-app-gray rounded flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Not playing</p>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Player Controls -->
            <div class="flex-1 max-w-xl mx-4">
                <!-- Control Buttons -->
                <div class="flex items-center justify-center gap-4 mb-2">
                    <!-- Shuffle -->
                    <button @click="$store.player.toggleShuffle()" class="player-control"
                        :class="{ 'text-brand-orange': $store.player.shuffle }">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M10.59 9.17L5.41 4 4 5.41l5.17 5.17 1.42-1.41zM14.5 4l2.04 2.04L4 18.59 5.41 20 17.96 7.46 20 9.5V4h-5.5zm.33 9.41l-1.41 1.41 3.13 3.13L14.5 20H20v-5.5l-2.04 2.04-3.13-3.13z" />
                        </svg>
                    </button>

                    <!-- Previous -->
                    <button @click="$store.player.prev()" class="player-control">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z" />
                        </svg>
                    </button>

                    <!-- Play/Pause -->
                    <button @click="$store.player.toggle()"
                        class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center hover:scale-105 transition-transform">
                        <template x-if="!$store.player.isPlaying">
                            <svg class="w-5 h-5 text-black ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5.14v14l11-7-11-7z" />
                            </svg>
                        </template>
                        <template x-if="$store.player.isPlaying">
                            <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                            </svg>
                        </template>
                    </button>

                    <!-- Next -->
                    <button @click="$store.player.next()" class="player-control">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z" />
                        </svg>
                    </button>

                    <!-- Repeat -->
                    <button @click="$store.player.cycleRepeat()" class="player-control relative"
                        :class="{ 'text-brand-orange': $store.player.repeat !== 'none' }">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 7h10v3l4-4-4-4v3H5v6h2V7zm10 10H7v-3l-4 4 4 4v-3h12v-6h-2v4z" />
                        </svg>
                        <span x-show="$store.player.repeat === 'one'"
                            class="absolute -top-1 -right-1 text-xs font-bold text-brand-orange">1</span>
                    </button>
                </div>

                <!-- Progress Bar -->
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-400 w-10 text-right"
                        x-text="$store.player.formatTime($store.player.currentTime)">0:00</span>
                    <div class="progress-bar flex-1"
                        @click="$store.player.seek($event.offsetX / $event.target.offsetWidth)">
                        <div class="progress-fill" :style="{ width: $store.player.progress + '%' }"></div>
                    </div>
                    <span class="text-xs text-gray-400 w-10"
                        x-text="$store.player.formatTime($store.player.duration)">0:00</span>
                </div>
            </div>

            <!-- Volume & Queue -->
            <div class="flex items-center gap-4 w-72 justify-end">
                <!-- Queue Button -->
                <button @click="showQueue = !showQueue" class="player-control"
                    :class="{ 'text-brand-orange': showQueue }">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M15 6H3v2h12V6zm0 4H3v2h12v-2zM3 16h8v-2H3v2zM17 6v8.18c-.31-.11-.65-.18-1-.18-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3V8h3V6h-5z" />
                    </svg>
                </button>

                <!-- Volume Control -->
                <div class="flex items-center gap-2">
                    <button @click="$store.player.toggleMute()" class="player-control">
                        <template x-if="$store.player.isMuted || $store.player.volume === 0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51A8.796 8.796 0 0021 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06a8.99 8.99 0 003.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z" />
                            </svg>
                        </template>
                        <template
                            x-if="!$store.player.isMuted && $store.player.volume > 0 && $store.player.volume < 0.5">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.5 12c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM5 9v6h4l5 5V4L9 9H5z" />
                            </svg>
                        </template>
                        <template x-if="!$store.player.isMuted && $store.player.volume >= 0.5">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z" />
                            </svg>
                        </template>
                    </button>
                    <div class="relative w-24 h-1 bg-gray-600 rounded-full cursor-pointer group hover:h-2 transition-all"
                        @click="$store.player.setVolume($event.offsetX / $event.target.offsetWidth)">
                        <div class="absolute left-0 top-0 h-full bg-white rounded-full transition-all"
                            :style="'width: ' + (($store.player.isMuted ? 0 : $store.player.volume) * 100) + '%'"></div>
                        <div class="absolute top-1/2 -translate-y-1/2 w-3 h-3 bg-white rounded-full shadow opacity-0 group-hover:opacity-100 transition-opacity"
                            :style="'left: calc(' + (($store.player.isMuted ? 0 : $store.player.volume) * 100) + '% - 6px)'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Queue Panel -->
    <div x-show="showQueue" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="absolute bottom-full right-2 sm:right-4 mb-2 w-72 sm:w-80 max-h-80 sm:max-h-96 bg-app-gray rounded-lg shadow-2xl overflow-hidden">
        <div class="p-4 border-b border-gray-700">
            <h3 class="font-semibold text-white">Queue</h3>
        </div>
        <div class="overflow-y-auto max-h-60 sm:max-h-72">
            <template x-if="$store.player.queue.length === 0">
                <p class="p-4 text-center text-gray-500 text-sm">Queue is empty</p>
            </template>
            <template x-for="(song, index) in $store.player.queue" :key="index">
                <div class="flex items-center gap-3 p-3 hover:bg-white/5 cursor-pointer"
                    :class="{ 'bg-white/10': index === $store.player.queueIndex }"
                    @click="$store.player.playQueue($store.player.queue, index)">
                    <img :src="song.cover_url || '/images/default-album-cover.png'"
                        class="w-10 h-10 rounded object-cover" onerror="this.src='/images/default-album-cover.png'">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-white truncate" x-text="song.title"></p>
                        <p class="text-xs text-gray-500 truncate" x-text="song.artist || 'Unknown Artist'"></p>
                    </div>
                    <button @click.stop="$store.player.removeFromQueue(index)" class="text-gray-500 hover:text-white">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>
    </div>
</div>