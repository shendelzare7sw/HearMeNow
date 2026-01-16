<!-- Top Navigation Bar -->
<header class="glass sticky top-0 z-40 px-4 sm:px-6 py-3 sm:py-4">
    <div class="flex items-center justify-between">
        <!-- Left Section -->
        <div class="flex items-center gap-2 sm:gap-4">
            <!-- Mobile Menu Button -->
            <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                </svg>
            </button>

            <!-- Navigation Arrows (Desktop) -->
            <div class="hidden sm:flex items-center gap-2">
                <button onclick="history.back()" class="w-8 h-8 flex items-center justify-center bg-black/70 rounded-full hover:bg-black transition-colors">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                    </svg>
                </button>
                <button onclick="history.forward()" class="w-8 h-8 flex items-center justify-center bg-black/70 rounded-full hover:bg-black transition-colors">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Search Bar (shown on search page - Desktop) -->
        @if(request()->routeIs('library.search'))
        <div class="hidden sm:block flex-1 max-w-md mx-8">
            <form action="{{ route('library.search') }}" method="GET" class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10.533 1.279c-5.18 0-9.407 4.14-9.407 9.279s4.226 9.279 9.407 9.279c2.234 0 4.29-.77 5.907-2.058l4.353 4.353a1 1 0 101.414-1.414l-4.344-4.344a9.157 9.157 0 002.077-5.816c0-5.14-4.226-9.28-9.407-9.28z"/>
                </svg>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}"
                       placeholder="What do you want to listen to?" 
                       class="w-full pl-10 pr-4 py-3 bg-white rounded-full text-black text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-orange">
            </form>
        </div>
        @endif

        <!-- User Menu -->
        <div class="flex items-center gap-2 sm:gap-4">
            @guest
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white font-semibold transition-colors text-sm sm:text-base">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="btn-primary text-xs sm:text-sm py-2 px-3 sm:px-4">
                    Sign up
                </a>
            @else
                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false, showLogoutModal: false }">
                    <button @click="open = !open" 
                            @click.away="open = false"
                            class="flex items-center gap-2 bg-black hover:bg-app-gray rounded-full p-1 pr-2 sm:pr-3 transition-colors">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" 
                                 alt="{{ auth()->user()->name }}"
                                 class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full gradient-bg flex items-center justify-center text-black font-semibold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="hidden sm:inline text-sm font-semibold text-white">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 text-white transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-app-gray rounded-md shadow-lg py-1 z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-white hover:bg-white/10">
                            Profile
                        </a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-white hover:bg-white/10">
                                Admin Dashboard
                            </a>
                        @endif
                        <div class="border-t border-gray-700 my-1"></div>
                        <button type="button" 
                                @click="open = false; showLogoutModal = true"
                                class="w-full text-left px-4 py-2 text-sm text-white hover:bg-white/10">
                            Log out
                        </button>
                    </div>

                    <!-- Logout Confirmation Modal -->
                    <div x-show="showLogoutModal" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4"
                         @keydown.escape.window="showLogoutModal = false"
                         style="display: none;">
                        <div class="bg-app-gray rounded-lg shadow-2xl max-w-sm w-full p-6"
                             @click.away="showLogoutModal = false">
                            <div class="text-center">
                                <div class="w-16 h-16 mx-auto mb-4 gradient-bg rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-black" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-2">Log Out</h3>
                                <p class="text-gray-400 mb-6">Are you sure you want to log out?</p>
                                <div class="flex gap-3">
                                    <button type="button" 
                                            @click="showLogoutModal = false"
                                            class="flex-1 py-2 px-4 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-full transition-colors">
                                        Cancel
                                    </button>
                                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full py-2 px-4 gradient-bg hover:opacity-90 text-black font-semibold rounded-full transition-opacity">
                                            Yes, Log out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</header>