<aside class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-black flex flex-col h-full transition-transform duration-300 lg:translate-x-0 border-r border-gray-900"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    <!-- Close Button (Mobile) -->
    <button @click="sidebarOpen = false" 
            class="lg:hidden absolute top-4 right-4 text-gray-400 hover:text-white z-10">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
    </button>

    <!-- Logo -->
    <div class="px-6 pt-6 pb-2">
        <a href="{{ route('stream.home') }}" class="flex items-center gap-3" @click="sidebarOpen = false">
            <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                </svg>
            </div>
            <span class="text-xl font-bold text-white tracking-tight">HearMeNow</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 overflow-y-auto space-y-6 pt-6 custom-scrollbar">
        
        <!-- Online Streaming -->
        <div>
            <div class="px-3 mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">
                Discover
            </div>
            <div class="space-y-0.5">
                <a href="{{ route('stream.home') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('stream.home') ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}" @click="sidebarOpen = false">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 5.69l5 4.5V18h-2v-6H9v6H7v-7.81l5-4.5M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z"/></svg>
                    <span>Home</span>
                </a>
                <a href="{{ route('stream.browse') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('stream.browse') ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}" @click="sidebarOpen = false">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 10.9c-.61 0-1.1.49-1.1 1.1s.49 1.1 1.1 1.1c.61 0 1.1-.49 1.1-1.1s-.49-1.1-1.1-1.1zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm2.19 12.19L6 18l3.81-8.19L18 6l-3.81 8.19z"/></svg>
                    <span>Browse</span>
                </a>
                <a href="{{ route('stream.playlists') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('stream.playlists') ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}" @click="sidebarOpen = false">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M15 6H3v2h12V6zm0 4H3v2h12v-2zM3 16h8v-2H3v2zM17 6v8.18c-.31-.11-.65-.18-1-.18-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3V8h3V6h-5z"/></svg>
                    <span>Playlists</span>
                </a>

            </div>
        </div>

        <!-- My Music (Local) -->
        <div>
            <div class="px-3 mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center justify-between">
                <span>My Library</span>
                <span class="bg-gray-800 text-gray-400 text-[10px] px-1.5 py-0.5 rounded border border-gray-700">LOCAL</span>
            </div>
            <div class="space-y-0.5">
                <a href="{{ route('library.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('library.index', 'dashboard') ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}" @click="sidebarOpen = false">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.5 3.247a1 1 0 00-1 0L4 7.577V20h4.5v-6a1 1 0 011-1h5a1 1 0 011 1v6H20V7.577l-7.5-4.33z"/></svg>
                    <span>Your Space</span>
                </a>
                <a href="{{ route('playlists.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('playlists.*') && !request()->routeIs('playlists.create') ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}" @click="sidebarOpen = false">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M15 6H3v2h12V6zm0 4H3v2h12v-2zM3 16h8v-2H3v2zM17 6v8.18c-.31-.11-.65-.18-1-.18-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3V8h3V6h-5z"/></svg>
                    <span>Playlists</span>
                </a>
                <a href="{{ route('library.search') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('library.search') ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}" @click="sidebarOpen = false">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                    <span>Search</span>
                </a>
                <a href="{{ route('library.upload') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('library.upload') ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}" @click="sidebarOpen = false">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16h6v-6h4l-7-7-7 7h4v6zm-4 2h14v2H5v-2z"/></svg>
                    <span>Upload</span>
                </a>
            </div>
        </div>

        <!-- Create Playlist Button -->
        <div class="px-1">
             <a href="{{ route('playlists.create') }}" class="flex items-center gap-3 px-4 py-3 bg-white/5 hover:bg-white/10 rounded-lg transition-colors group text-sm font-medium text-gray-300 hover:text-white border border-white/5" @click="sidebarOpen = false">
                <div class="w-6 h-6 bg-app-orange rounded flex items-center justify-center text-black">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <span>Create Playlist</span>
            </a>
        </div>
        
        <!-- Admin & User Playlists -->
        @auth
            <!-- Admin -->
            @if(auth()->user()->is_admin)
            <div>
                <div class="px-3 mb-2 text-xs font-bold text-red-500 uppercase tracking-wider">
                    Admin
                </div>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                        <span>Admin Panel</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Your Playlists -->
            <div class="border-t border-gray-900 pt-4 pb-8">
                 <div class="px-3 mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">
                    Your Playlists
                </div>
                <div class="space-y-0.5 max-h-48 overflow-y-auto custom-scrollbar">
                    @foreach(auth()->user()->playlists()->latest()->withCount('songs')->take(20)->get() as $playlist)
                        <a href="{{ route('playlists.show', $playlist) }}" class="block px-3 py-1.5 text-sm text-gray-400 hover:text-white truncate transition-colors" @click="sidebarOpen = false">
                            {{ $playlist->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endauth

    </nav>
</aside>