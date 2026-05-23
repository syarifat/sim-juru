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
            
            <div class="h-16 flex items-center justify-center border-b border-slate-800 relative">
                <span x-show="desktopSidebarOpen" class="text-2xl font-bold tracking-wider text-emerald-400">SIMJURU</span>
                <span x-show="!desktopSidebarOpen" class="text-2xl font-bold text-emerald-400">SJ</span>
                
                <button @click="desktopSidebarOpen = !desktopSidebarOpen" class="absolute -right-3 top-5 bg-slate-800 text-gray-400 hover:text-white rounded-full p-1 border border-slate-700 hidden md:flex items-center justify-center shadow-md">
                    <svg x-show="desktopSidebarOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    <svg x-show="!desktopSidebarOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
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
                <span class="text-xl font-bold text-emerald-400">Menu SIMJURU</span>
                <button @click="mobileMenuOpen = false" class="text-gray-400 hover:text-white focus:outline-none">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto py-4">
                @include('layouts.navigation')
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300 h-screen overflow-hidden">
            
            <!-- Mobile Topbar -->
            <div class="md:hidden flex items-center justify-between px-4 py-3 bg-white shadow-sm z-10 border-b border-gray-100">
                <span class="text-xl font-extrabold text-emerald-700 tracking-wider">SIMJURU</span>
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
