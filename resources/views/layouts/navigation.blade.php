@php
    $role = Auth::user()->role ?? '';
    // Styling class agar seragam
    $linkClass = "group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors hover:bg-emerald-600 hover:text-white text-gray-300 mx-2 mb-1";
    $iconClass = "mr-3 h-5 w-5 flex-shrink-0";
    $textShowCondition = "desktopSidebarOpen || mobileMenuOpen";
@endphp

<nav class="space-y-1">
    
    <a href="{{ route('dashboard') }}" class="{{ $linkClass }} {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white' : '' }}">
        <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span x-show="{{ $textShowCondition }}">Dashboard</span>
    </a>

    @if($role === 'Admin')
        <div x-show="{{ $textShowCondition }}" class="pt-5 pb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Master Data</div>
        
        <a href="{{ route('admin.users.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.users.*') ? 'bg-emerald-600 text-white' : '' }}">
            <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span x-show="{{ $textShowCondition }}">Pengguna & Guru</span>
        </a>
        <a href="{{ route('admin.tahun-ajaran.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.tahun-ajaran.*') ? 'bg-emerald-600 text-white' : '' }}">
            <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span x-show="{{ $textShowCondition }}">Tahun Ajaran</span>
        </a>
        <a href="{{ route('admin.kelas-mapel.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.kelas-mapel.*', 'admin.kelas.*', 'admin.mapel.*') ? 'bg-emerald-600 text-white' : '' }}">
            <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <span x-show="{{ $textShowCondition }}">Kelas & Mapel</span>
        </a>
        <a href="{{ route('admin.jam-pelajaran.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.jam-pelajaran.*') ? 'bg-emerald-600 text-white' : '' }}">
            <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span x-show="{{ $textShowCondition }}">Jam Pelajaran</span>
        </a>

        <div x-show="{{ $textShowCondition }}" class="pt-5 pb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Transaksi</div>
        
        <a href="{{ route('admin.jadwal.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.jadwal.*') ? 'bg-emerald-600 text-white' : '' }}">
            <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <span x-show="{{ $textShowCondition }}">Jadwal Pelajaran</span>
        </a>
        <a href="{{ route('admin.guru-pengganti.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.guru-pengganti.*') ? 'bg-emerald-600 text-white' : '' }}">
            <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            <span x-show="{{ $textShowCondition }}">Guru Pengganti</span>
        </a>

    @endif

    @if($role === 'Guru')
        <div x-show="{{ $textShowCondition }}" class="pt-5 pb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Menu Utama</div>
        
        <a href="{{ route('guru.jurnal.index') }}" class="{{ $linkClass }} {{ request()->routeIs('guru.jurnal.index', 'guru.jurnal.create') ? 'bg-emerald-600 text-white' : '' }}">
            <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            <span x-show="{{ $textShowCondition }}">Jadwal & Isi Jurnal</span>
        </a>
        <a href="{{ route('guru.jurnal.riwayat') }}" class="{{ $linkClass }} {{ request()->routeIs('guru.jurnal.riwayat') ? 'bg-emerald-600 text-white' : '' }}">
            <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span x-show="{{ $textShowCondition }}">Riwayat Jurnal</span>
        </a>
    @endif

    @if($role === 'Kepala_Sekolah')
        <div x-show="{{ $textShowCondition }}" class="pt-5 pb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Evaluasi</div>
        
        <a href="{{ route('kepsek.validasi.index') }}" class="{{ $linkClass }} {{ request()->routeIs('kepsek.validasi.*') ? 'bg-emerald-600 text-white' : '' }}">
            <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span x-show="{{ $textShowCondition }}">Validasi Jurnal</span>
        </a>
    @endif

    @if(in_array($role, ['Admin', 'Kepala_Sekolah']))
        <div x-show="{{ $textShowCondition }}" class="pt-5 pb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Rekapitulasi</div>
        <a href="{{ route('admin.laporan.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.laporan.*') ? 'bg-emerald-600 text-white' : '' }}">
            <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span x-show="{{ $textShowCondition }}">Laporan Jurnal</span>
        </a>
    @endif

</nav>

