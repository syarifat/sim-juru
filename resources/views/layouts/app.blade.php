<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIMJURU') }}</title>
    <link rel="icon" type="image/png" href="/logo.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50"
      x-data="{
          mobileMenuOpen: false,
          desktopSidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
          toggleSidebar() {
              this.desktopSidebarOpen = !this.desktopSidebarOpen;
              localStorage.setItem('sidebarOpen', this.desktopSidebarOpen);
          }
      }">
    <div class="h-screen flex w-full overflow-hidden">

        {{-- ======= DESKTOP SIDEBAR ======= --}}
        <aside class="bg-slate-900 text-white flex-shrink-0 transition-all duration-300 hidden md:flex flex-col h-full relative z-20"
               :class="desktopSidebarOpen ? 'w-64' : 'w-20'">

            {{-- Logo + Toggle Button --}}
            <div class="h-16 flex items-center justify-center border-b border-slate-800 flex-shrink-0 relative">
                <div x-show="desktopSidebarOpen" x-cloak class="flex items-center space-x-2.5">
                    <img src="/logo.png" alt="Logo" class="w-8 h-8 object-contain flex-shrink-0">
                    <span class="text-lg font-extrabold tracking-wider text-white">SIMJURU</span>
                </div>
                <img x-show="!desktopSidebarOpen" x-cloak src="/logo.png" alt="Logo" class="w-8 h-8 object-contain">

                <button @click="toggleSidebar()"
                        class="absolute -right-3 top-1/2 -translate-y-1/2 bg-slate-800 text-gray-400 hover:text-white rounded-full p-1 border border-slate-700 flex items-center justify-center shadow-md z-30">
                    <svg x-show="desktopSidebarOpen" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    <svg x-show="!desktopSidebarOpen" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>

            {{-- Scrollable nav area --}}
            <div class="flex-1 overflow-y-auto overflow-x-hidden py-2">
                @include('layouts.navigation')
            </div>

            {{-- Fixed profile + logout at bottom --}}
            <div class="flex-shrink-0 border-t border-slate-800 p-3">
                <div x-show="desktopSidebarOpen" x-cloak class="px-2 mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->username ?? 'G', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->username ?? 'Guest' }}</p>
                            <p class="text-xs text-emerald-400 font-medium">{{ str_replace('_', ' ', Auth::user()->role ?? '') }}</p>
                        </div>
                    </div>
                </div>
                <div x-show="!desktopSidebarOpen" x-cloak class="flex justify-center mb-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(Auth::user()->username ?? 'G', 0, 1)) }}
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center px-2 py-2 text-sm font-medium rounded-lg text-red-400 hover:bg-red-950 hover:text-red-300 transition-colors"
                            :class="desktopSidebarOpen ? 'justify-start' : 'justify-center'">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span x-show="desktopSidebarOpen" x-cloak class="ml-3">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ======= MOBILE OVERLAY + SIDEBAR ======= --}}
        <div x-show="mobileMenuOpen"
             class="fixed inset-0 z-40 bg-gray-900/80 backdrop-blur-sm md:hidden"
             x-transition.opacity
             @click="mobileMenuOpen = false"></div>

        <aside x-show="mobileMenuOpen"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white flex flex-col md:hidden"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full">

            <div class="h-16 flex items-center justify-between px-4 border-b border-slate-800 flex-shrink-0">
                <div class="flex items-center space-x-2.5">
                    <img src="/logo.png" alt="Logo" class="w-8 h-8 object-contain">
                    <span class="text-lg font-extrabold text-white">SIMJURU</span>
                </div>
                <button @click="mobileMenuOpen = false" class="text-gray-400 hover:text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto py-2">
                @include('layouts.navigation')
            </div>

            <div class="flex-shrink-0 border-t border-slate-800 p-3">
                <div class="px-2 mb-3 flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->username ?? 'G', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->username ?? 'Guest' }}</p>
                        <p class="text-xs text-emerald-400 font-medium">{{ str_replace('_', ' ', Auth::user()->role ?? '') }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-2 py-2 text-sm font-medium rounded-lg text-red-400 hover:bg-red-950 hover:text-red-300 transition-colors">
                        <svg class="w-5 h-5 flex-shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- ======= MAIN CONTENT ======= --}}
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">

            {{-- Mobile topbar --}}
            <div class="md:hidden flex-shrink-0 flex items-center justify-between px-4 py-3 bg-white shadow-sm border-b border-gray-100 z-10">
                <div class="flex items-center space-x-2">
                    <img src="/logo.png" alt="Logo" class="w-7 h-7 object-contain">
                    <span class="text-lg font-extrabold text-slate-800 tracking-wide">SIMJURU</span>
                </div>
                <button @click="mobileMenuOpen = true" class="text-gray-600 hover:text-emerald-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

            <main class="flex-1 p-4 md:p-6 overflow-y-auto bg-gray-50">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
