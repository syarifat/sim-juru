<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Ringkasan Mengajar Hari Ini -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 h-full flex flex-col justify-center">
        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Tugas Mengajar Hari Ini
        </h3>
        
        <div class="flex items-center justify-between">
            <div class="text-center">
                <div class="text-4xl font-black text-blue-600">{{ $totalJadwalHariIni ?? 0 }}</div>
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-2">Jadwal Kelas</div>
            </div>
            
            <div class="text-gray-300 font-light text-4xl">/</div>
            
            <div class="text-center">
                <div class="text-4xl font-black {{ ($totalJurnalTerisi ?? 0) === ($totalJadwalHariIni ?? 0) && ($totalJadwalHariIni ?? 0) > 0 ? 'text-emerald-500' : 'text-amber-500' }}">
                    {{ $totalJurnalTerisi ?? 0 }}
                </div>
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-2">Jurnal Terisi</div>
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('guru.jurnal.index') }}" class="w-full inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Lihat Jadwal & Isi Jurnal
            </a>
        </div>
    </div>

    <!-- Informasi / Notifikasi -->
    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-6 shadow-sm border border-emerald-100 flex flex-col justify-center">
        <div class="bg-emerald-100 text-emerald-600 w-12 h-12 rounded-full flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-emerald-900 mb-2">Penting!</h3>
        <p class="text-emerald-800 text-sm leading-relaxed">
            Pastikan Anda selalu mengisi <strong>Jurnal Mengajar</strong> setiap kali selesai melaksanakan tugas kelas hari ini. Jurnal yang belum disetujui Kepala Sekolah dapat Anda temukan status revisinya melalui menu <strong>Riwayat Jurnal</strong>.
        </p>
    </div>
</div>
