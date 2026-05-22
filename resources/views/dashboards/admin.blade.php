<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow">
        <div class="bg-blue-50 text-blue-600 p-4 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <div class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Guru</div>
            <div class="text-3xl font-black text-gray-800">{{ $totalGuru ?? 0 }}</div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow">
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <div>
            <div class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Kelas</div>
            <div class="text-3xl font-black text-gray-800">{{ $totalKelas ?? 0 }}</div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow">
        <div class="bg-purple-50 text-purple-600 p-4 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <div>
            <div class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Mata Pelajaran</div>
            <div class="text-3xl font-black text-gray-800">{{ $totalMapel ?? 0 }}</div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition-shadow">
        <div class="bg-amber-50 text-amber-600 p-4 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
        <div>
            <div class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Jadwal</div>
            <div class="text-3xl font-black text-gray-800">{{ $totalJadwal ?? 0 }}</div>
        </div>
    </div>
</div>

<div class="mt-8 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Informasi Sistem
    </h3>
    <div class="prose max-w-none text-gray-600 text-sm">
        <p>Anda login sebagai <strong>Administrator</strong>. Anda memiliki kendali penuh atas Master Data, penjadwalan, dan laporan sistem.</p>
        <p>Gunakan menu di bilah kiri untuk mengelola data Guru, Kelas, Mata Pelajaran, serta mengatur Jadwal Pelajaran dan Guru Pengganti secara berkala.</p>
    </div>
</div>
