<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login &mdash; SIMJURU</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased bg-slate-950 min-h-screen flex">

    {{-- Left Panel - Branding --}}
    <div class="hidden lg:flex w-1/2 relative overflow-hidden flex-col justify-between p-12">
        {{-- Background gradient --}}
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-600 via-emerald-700 to-slate-900"></div>
        {{-- Pattern overlay --}}
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 32px 32px;"></div>
        {{-- Glow effects --}}
        <div class="absolute top-1/3 left-1/4 w-72 h-72 bg-emerald-400 rounded-full opacity-20 blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-56 h-56 bg-teal-300 rounded-full opacity-15 blur-3xl"></div>

        {{-- Top logo --}}
        <div class="relative z-10">
            <div class="flex items-center space-x-3">
                <img src="/logo.png" alt="SIMJURU" class="w-12 h-12 object-contain drop-shadow-lg">
                <span class="text-white font-bold text-xl tracking-wide">SIMJURU</span>
            </div>
        </div>

        {{-- Center text --}}
        <div class="relative z-10">
            <h1 class="text-5xl font-black text-white leading-tight mb-6">
                Sistem Informasi<br>
                <span class="text-emerald-200">Jurnal Guru</span>
            </h1>
            <p class="text-emerald-100/80 text-lg leading-relaxed max-w-sm">
                Platform manajemen jurnal mengajar yang modern, efisien, dan mudah digunakan.
            </p>

            {{-- Stats --}}
            <div class="mt-10 grid grid-cols-3 gap-4">
                <div class="bg-white/10 backdrop-blur rounded-xl p-4 text-center">
                    <div class="text-2xl font-black text-white">3</div>
                    <div class="text-xs text-emerald-200 mt-1 font-medium">Role Akses</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-4 text-center">
                    <div class="text-2xl font-black text-white">∞</div>
                    <div class="text-xs text-emerald-200 mt-1 font-medium">Jurnal Harian</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-4 text-center">
                    <div class="text-2xl font-black text-white">PDF</div>
                    <div class="text-xs text-emerald-200 mt-1 font-medium">Laporan Instan</div>
                </div>
            </div>
        </div>

        {{-- Bottom footer --}}
        <div class="relative z-10">
            <p class="text-emerald-200/60 text-sm">&copy; {{ date('Y') }} SIMJURU. All rights reserved.</p>
        </div>
    </div>

    {{-- Right Panel - Login Form --}}
    <div class="flex-1 flex items-center justify-center p-6 lg:p-12 bg-slate-950">
        <div class="w-full max-w-md">

            {{-- Mobile logo --}}
            <div class="flex justify-center mb-8 lg:hidden">
                <div class="flex items-center space-x-3">
                    <img src="/logo.png" alt="SIMJURU" class="w-12 h-12 object-contain">
                    <span class="text-white font-black text-2xl">SIMJURU</span>
                </div>
            </div>

            {{-- Heading --}}
            <div class="mb-8">
                <h2 class="text-3xl font-black text-white">Selamat Datang</h2>
                <p class="text-slate-400 mt-2">Masuk untuk melanjutkan ke dashboard Anda.</p>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-900/40 border border-emerald-700/50 rounded-xl text-emerald-300 text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-900/40 border border-red-700/50 rounded-xl text-red-300 text-sm font-medium flex items-start space-x-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-300 mb-2">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input id="username" name="username" type="text"
                            value="{{ old('username') }}"
                            required autofocus autocomplete="username"
                            placeholder="Masukkan username"
                            class="w-full pl-10 pr-4 py-3 bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-sm">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-300 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" name="password" type="password"
                            required autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="w-full pl-10 pr-4 py-3 bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-sm">
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-slate-900">
                    <label for="remember_me" class="ml-2.5 text-sm text-slate-400 cursor-pointer select-none">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-all duration-200 shadow-lg shadow-emerald-900/40 hover:shadow-emerald-800/60 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-950 active:scale-[0.98] flex items-center justify-center space-x-2">
                    <span>Masuk</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </form>

            <p class="text-center text-xs text-slate-600 mt-8">Hanya pengguna terdaftar yang dapat mengakses sistem ini.</p>
        </div>
    </div>

</body>
</html>
