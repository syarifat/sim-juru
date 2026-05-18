<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIMJURU') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ mobileMenuOpen: false, desktopSidebarOpen: true }">
    <div class="min-h-screen flex w-full overflow-hidden">

        <aside class="bg-slate-900 text-white flex-shrink-0 transition-all duration-300 hidden md:flex flex-col relative z-20"
               :class="desktopSidebarOpen ? 'w-64' : 'w-20'">
            
            <div class="h-16 flex items-center justify-center border-b border-slate-800">
                <span x-show="desktopSidebarOpen" class="text-2xl font-bold tracking-wider text-blue-400">SIMJURU</span>
                <span x-show="!desktopSidebarOpen" class="text-2xl font-bold text-blue-400">SJ</span>
            </div>

            <div class="flex-1 overflow-y-auto py-4 overflow-x-hidden">
                @include('layouts.navigation')
            </div>
        </aside>

        <div x-show="mobileMenuOpen" 
             class="fixed inset-0 z-40 bg-gray-900/80 backdrop-blur-sm md:hidden" 
             x-transition.opacity 
             @click="mobileMenuOpen = false"></div>
        
        <aside x-show="mobileMenuOpen" 
               class="fixed inset-y-0 right-0 z-50 w-64 bg-slate-900 text-white transform transition-transform duration-300 md:hidden flex flex-col"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="translate-x-full">
            
            <div class="h-16 flex items-center justify-between px-4 border-b border-slate-800">
                <span class="text-xl font-bold text-blue-400">Menu SIMJURU</span>
                <button @click="mobileMenuOpen = false" class="text-gray-400 hover:text-white focus:outline-none">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto py-4">
                @include('layouts.navigation')
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300 h-screen overflow-hidden">
            
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-4 md:px-6 z-10">
                
                <button @click="desktopSidebarOpen = !desktopSidebarOpen" class="hidden md:block text-gray-500 hover:text-blue-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </button>

                <div class="flex items-center md:hidden w-full justify-between">
                    <span class="text-xl font-extrabold text-blue-700 tracking-wider">SIMJURU</span>
                    <button @click="mobileMenuOpen = true" class="text-gray-600 hover:text-blue-600 focus:outline-none">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-700 uppercase">{{ Auth::user()->username ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-500">{{ str_replace('_', ' ', Auth::user()->role ?? '') }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 p-4 md:p-6 overflow-y-auto bg-gray-50">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>