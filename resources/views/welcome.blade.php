<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HearMeNow') }} - Your Personal Music Player</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-app-dark font-sans text-white antialiased selection:bg-brand-orange selection:text-white">
    <!-- Navigation -->
    <nav x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
        class="fixed top-0 left-0 right-0 z-50 px-4 sm:px-6 lg:px-8 py-4 transition-all duration-300"
        :class="{ 'bg-black/80 backdrop-blur-md shadow-lg py-3': scrolled, 'bg-transparent py-5': !scrolled }">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300">
                    <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-white tracking-tight">HearMeNow</span>
            </a>

            <!-- Nav Links -->
            <div class="flex items-center gap-3 sm:gap-6">
                @auth
                    <a href="{{ route('stream.home') }}" class="btn-primary text-sm py-2 px-6 shadow-glow">
                        Open App
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-gray-300 hover:text-white font-medium transition-colors text-sm sm:text-base hover:tracking-wide duration-300">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm py-2 px-6 shadow-glow">
                        Sign up
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center px-4 sm:px-6 pt-20 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-1/4 -left-20 w-72 h-72 bg-brand-orange/20 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-1/4 -right-20 w-96 h-96 bg-brand-green/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
        
        <div class="max-w-5xl mx-auto text-center relative z-10">
            <!-- Main Content -->
            <div class="mb-10">
                <h1 class="text-5xl sm:text-7xl font-extrabold text-white mb-8 leading-tight tracking-tight">
                    Your Music <br class="hidden sm:block" />
                    <span class="relative inline-flex items-center gap-4 sm:gap-6">
                        <!-- Left Spectrum Bars (Wave Animation) -->
                        <span class="flex items-end gap-1 h-16 sm:h-20" aria-hidden="true">
                            <span class="spectrum-bar-wave" style="animation-delay: 0s;"></span>
                            <span class="spectrum-bar-wave" style="animation-delay: 0.1s;"></span>
                            <span class="spectrum-bar-wave" style="animation-delay: 0.2s;"></span>
                            <span class="spectrum-bar-wave" style="animation-delay: 0.3s;"></span>
                            <span class="spectrum-bar-wave" style="animation-delay: 0.4s;"></span>
                        </span>
                        
                        <!-- Animated Text with Color Pulse -->
                        <span class="gradient-text-pulse">
                            Your Way
                        </span>
                        
                        <!-- Right Spectrum Bars (Wave Animation) -->
                        <span class="flex items-end gap-1 h-16 sm:h-20" aria-hidden="true">
                            <span class="spectrum-bar-wave" style="animation-delay: 0.5s;"></span>
                            <span class="spectrum-bar-wave" style="animation-delay: 0.6s;"></span>
                            <span class="spectrum-bar-wave" style="animation-delay: 0.7s;"></span>
                            <span class="spectrum-bar-wave" style="animation-delay: 0.8s;"></span>
                            <span class="spectrum-bar-wave" style="animation-delay: 0.9s;"></span>
                        </span>
                    </span>
                </h1>
                
                <p class="text-lg sm:text-xl text-gray-400 max-w-2xl mx-auto mb-10 leading-relaxed font-light">
                    Upload, organize, and stream your personal music collection from anywhere.
                    A private music player built just for you.
                </p>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mb-16">
                @auth
                    <a href="{{ route('stream.home') }}" class="btn-primary text-lg px-8 py-4 w-full sm:w-auto shadow-2xl shadow-brand-orange/20 hover:shadow-brand-orange/40 transition-all transform hover:-translate-y-1">
                       <span class="flex items-center gap-2 justify-center">
                           Go to Home
                           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                       </span>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary text-lg px-8 py-4 w-full sm:w-auto shadow-2xl shadow-brand-orange/20 hover:shadow-brand-orange/40 transition-all transform hover:-translate-y-1">
                        Get Started Free
                    </a>
                    <a href="{{ route('login') }}" class="group relative px-8 py-4 w-full sm:w-auto rounded-full overflow-hidden bg-gray-800 text-white font-semibold shadow-lg transition-all hover:bg-gray-700">
                        <span class="relative z-10 flex items-center justify-center gap-2 group-hover:text-brand-green transition-colors">
                            Sign In
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </span>
                    </a>
                @endauth
            </div>
            
            <!-- Features Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl mx-auto">
                 <div class="bg-gradient-to-br from-gray-900 to-black border border-white/5 p-6 rounded-2xl hover:border-brand-orange/30 transition-all duration-300 group">
                    <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                         <svg class="w-6 h-6 text-brand-orange" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16h6v-6h4l-7-7-7 7h4v6zm-4 2h14v2H5z"/></svg>
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">Cloud Upload</h3>
                    <p class="text-gray-500 text-sm">Store your MP3/FLAC files securely in your private cloud.</p>
                 </div>
                 
                 <div class="bg-gradient-to-br from-gray-900 to-black border border-white/5 p-6 rounded-2xl hover:border-brand-green/30 transition-all duration-300 group">
                    <div class="w-12 h-12 rounded-xl bg-green-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                         <svg class="w-6 h-6 text-brand-green" fill="currentColor" viewBox="0 0 24 24"><path d="M15 6H3v2h12V6zm0 4H3v2h12v-2zM3 16h8v-2H3v2zM17 6v8.18c-.31-.11-.65-.18-1-.18-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3V8h3V6h-5z"/></svg>
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">Organize</h3>
                    <p class="text-gray-500 text-sm">Create playlists and manage your library effortlessly.</p>
                 </div>

                 <div class="bg-gradient-to-br from-gray-900 to-black border border-white/5 p-6 rounded-2xl hover:border-brand-yellow/30 transition-all duration-300 group">
                    <div class="w-12 h-12 rounded-xl bg-yellow-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                         <svg class="w-6 h-6 text-brand-yellow" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12z"/></svg>
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">Smart Library</h3>
                    <p class="text-gray-500 text-sm">Auto-organize your collection into custom playlists.</p>
                 </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-10 border-t border-white/10 bg-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg gradient-bg flex items-center justify-center">
                    <span class="font-bold text-black text-xs">HM</span>
                </div>
                <span class="text-white font-semibold tracking-wide">HearMeNow</span>
            </div>
            
            <div class="flex gap-6 text-sm text-gray-500">
                <a href="#" class="hover:text-brand-green transition-colors">Privacy</a>
                <a href="#" class="hover:text-brand-green transition-colors">Terms</a>
                <a href="#" class="hover:text-brand-green transition-colors">Contact</a>
            </div>
            
            <p class="text-sm text-gray-600">
                © {{ date('Y') }} HearMeNow. All rights reserved.
            </p>
        </div>
    </footer>
    
    <style>
        .shadow-glow {
            box-shadow: 0 0 20px rgba(255, 140, 0, 0.3);
        }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
        }
        
        /* Animated Gradient Text with Vertical Movement */
        .gradient-text-pulse {
            background: linear-gradient(180deg, 
                #32CD32 0%,      /* Green at top */
                #FF8C00 40%,     /* Orange in middle */
                #FFD700 70%,     /* Yellow */
                #FFFFFF 100%     /* White at bottom */
            );
            background-size: 100% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-wave 1.2s ease-in-out infinite;
        }
        
        @keyframes gradient-wave {
            0% {
                background-position: 0% 0%;
            }
            25% {
                background-position: 0% 30%;
            }
            50% {
                background-position: 0% 50%;
            }
            75% {
                background-position: 0% 30%;
            }
            100% {
                background-position: 0% 0%;
            }
        }
        
        /* Music Spectrum Wave Animation (Left to Right) */
        .spectrum-bar-wave {
            display: inline-block;
            width: 6px;
            height: 100%;
            background: linear-gradient(to top, #FFD700, #FF8C00, #32CD32);
            border-radius: 3px;
            animation: spectrum-wave 1.2s ease-in-out infinite;
            transform-origin: bottom;
        }
        
        @keyframes spectrum-wave {
            0% {
                transform: scaleY(0.2);
                opacity: 0.4;
            }
            25% {
                transform: scaleY(0.8);
                opacity: 0.9;
            }
            50% {
                transform: scaleY(1);
                opacity: 1;
            }
            75% {
                transform: scaleY(0.6);
                opacity: 0.7;
            }
            100% {
                transform: scaleY(0.2);
                opacity: 0.4;
            }
        }
        
        @media (max-width: 640px) {
            .spectrum-bar-wave {
                width: 4px;
            }
        }
    </style>
</body>
</html>