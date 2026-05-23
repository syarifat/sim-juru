<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-emerald-600 to-indigo-700 rounded-2xl shadow-lg p-6 sm:p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-2 text-center md:text-left">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ Auth::user()->name ?? Auth::user()->username }}!</h1>
                <p class="text-emerald-100 font-medium opacity-90">
                    Sistem Informasi Manajemen Jurnal Guru (SIM-JURU)
                </p>
                @if(!$activeTahunAjaran)
                    <div class="mt-4 inline-flex items-center px-3 py-1 rounded-md bg-red-500/20 text-red-100 border border-red-500/30 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Belum ada Tahun Ajaran yang aktif.
                    </div>
                @else
                    <div class="mt-4 inline-flex items-center px-3 py-1 rounded-md bg-white/10 text-emerald-50 border border-white/20 text-sm backdrop-blur-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Tahun Ajaran Aktif: {{ $activeTahunAjaran->tahun }} (Semester {{ $activeTahunAjaran->semester }})
                    </div>
                @endif
            </div>
            <div class="hidden md:block">
                <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/20 text-center">
                    <div class="text-xs uppercase tracking-widest font-bold text-emerald-200 mb-1">Hari Ini</div>
                    <div class="text-2xl font-black">{{ $namaHari }}</div>
                    <div class="text-sm font-medium text-emerald-100">{{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Render partial view based on role -->
        @if($role === 'Admin')
            @include('dashboards.admin')
        @elseif($role === 'Guru')
            @include('dashboards.guru')
        @elseif($role === 'Kepala_Sekolah')
            @include('dashboards.kepsek')
        @endif

    </div>
</x-app-layout>
