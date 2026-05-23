<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800 flex items-center">
            <svg class="w-5 h-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            Rekap Jurnal Hari Ini
        </h3>
        <a href="{{ route('kepsek.validasi.index', ['tanggal' => $today]) }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
            Lihat Semua Validasi &rarr;
        </a>
    </div>

    <!-- Stat Cards untuk Kepsek -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Menunggu Validasi (Pending) -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-amber-100 flex flex-col justify-center items-center text-center relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-1 bg-amber-400"></div>
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Menunggu Validasi</div>
            <div class="text-4xl font-black text-amber-500">{{ $totalJurnalPending ?? 0 }}</div>
            <div class="text-sm text-gray-400 mt-1">Jurnal belum diperiksa</div>
        </div>

        <!-- Disetujui -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-emerald-100 flex flex-col justify-center items-center text-center relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-1 bg-emerald-400"></div>
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jurnal Disetujui</div>
            <div class="text-4xl font-black text-emerald-500">{{ $totalJurnalDisetujui ?? 0 }}</div>
            <div class="text-sm text-gray-400 mt-1">Telah divalidasi hari ini</div>
        </div>

        <!-- Direvisi -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-red-100 flex flex-col justify-center items-center text-center relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-1 bg-red-400"></div>
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Butuh Revisi</div>
            <div class="text-4xl font-black text-red-500">{{ $totalJurnalRevisi ?? 0 }}</div>
            <div class="text-sm text-gray-400 mt-1">Jurnal dikembalikan ke guru</div>
        </div>
    </div>

    <!-- Antrean Validasi Singkat -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-8">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h4 class="font-bold text-gray-800">Antrean Validasi Terbaru</h4>
            <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full border border-amber-200">Prioritas</span>
        </div>
        
        @if(isset($jurnalPendings) && count($jurnalPendings) > 0)
            <div class="divide-y divide-gray-100">
                @foreach($jurnalPendings as $jurnal)
                    <div class="p-4 hover:bg-gray-50 transition-colors flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-start gap-4">
                            <div class="bg-emerald-50 text-emerald-600 font-bold p-3 rounded-lg text-center min-w-[60px]">
                                <div class="text-[10px] uppercase">Jam</div>
                                <div class="text-lg">{{ $jurnal->jadwal->jam_ke_mulai }}</div>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $jurnal->guruPengisi->nama_lengkap }}</p>
                                <p class="text-sm text-gray-500">{{ $jurnal->jadwal->kelas->nama_kelas }} - {{ $jurnal->jadwal->mataPelajaran->nama_mapel }}</p>
                            </div>
                        </div>
                        <a href="{{ route('kepsek.validasi.edit', $jurnal) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-50 hover:text-emerald-600 transition-colors whitespace-nowrap">
                            Validasi
                            <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-emerald-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="font-medium">Hore! Semua jurnal hari ini sudah divalidasi.</p>
            </div>
        @endif
    </div>
</div>
