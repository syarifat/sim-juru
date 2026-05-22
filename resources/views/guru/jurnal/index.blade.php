<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Jadwal & Isi Jurnal</h2>
                <p class="text-sm text-gray-500">Daftar kelas yang harus Anda ajar hari ini.</p>
            </div>
            <div class="flex items-center space-x-2">
                <form method="GET" action="{{ route('guru.jurnal.index') }}" class="flex items-center space-x-2 bg-gray-50 p-1.5 rounded-lg border border-gray-200">
                    <label for="tanggal" class="text-sm font-semibold text-gray-600 px-2">Tanggal:</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}" class="px-3 py-1.5 text-sm border-none bg-white rounded focus:ring-2 focus:ring-blue-500 shadow-sm" onchange="this.form.submit()">
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg text-sm font-medium shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg text-sm font-medium shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-center shadow-sm">
            <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <div>
                <span class="text-sm text-blue-800">Menampilkan jadwal untuk hari:</span>
                <span class="font-bold text-blue-900">{{ $namaHari }}, {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @forelse($jadwals as $jadwal)
                @php
                    $waktuMulai = $jamPelajarans[$jadwal->jam_ke_mulai]->jam_mulai ?? null;
                    $waktuSelesai = $jamPelajarans[$jadwal->jam_ke_selesai]->jam_selesai ?? null;
                    $waktuMulaiStr = $waktuMulai ? \Carbon\Carbon::parse($waktuMulai)->format('H.i') : '-';
                    $waktuSelesaiStr = $waktuSelesai ? \Carbon\Carbon::parse($waktuSelesai)->format('H.i') : '-';
                    
                    $isFilled = $jurnalsFilled->has($jadwal->id);
                    $jurnal = $isFilled ? $jurnalsFilled->get($jadwal->id) : null;
                @endphp
                <div class="bg-white rounded-xl shadow-sm border {{ $isFilled ? 'border-emerald-200' : 'border-gray-200' }} overflow-hidden transition-all hover:shadow-md">
                    <div class="p-5">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            
                            <div class="flex items-start space-x-4">
                                <div class="bg-gray-50 rounded-lg p-3 text-center min-w-[100px] border border-gray-100">
                                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jam Ke</div>
                                    <div class="text-xl font-extrabold text-blue-600">{{ $jadwal->jam_ke_mulai }} - {{ $jadwal->jam_ke_selesai }}</div>
                                    <div class="text-xs text-gray-400 mt-1 font-medium">{{ $waktuMulaiStr }} s/d {{ $waktuSelesaiStr }}</div>
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2 mb-1">
                                        <h3 class="text-lg font-bold text-gray-800">{{ $jadwal->kelas->nama_kelas }}</h3>
                                        @if($jadwal->is_pengganti ?? false)
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-800 rounded-full uppercase tracking-wider">Pengganti</span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-semibold text-gray-600">{{ $jadwal->mataPelajaran->nama_mapel }}</p>
                                    @if($isFilled)
                                        <div class="mt-2 inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Jurnal telah diisi
                                            @if($jurnal->status_validasi === 'Disetujui')
                                                <span class="ml-2 text-emerald-600 font-bold">(Disetujui Kepsek)</span>
                                            @elseif($jurnal->status_validasi === 'Revisi')
                                                <span class="ml-2 text-red-600 font-bold">(Butuh Revisi)</span>
                                            @else
                                                <span class="ml-2 text-amber-600 font-bold">(Menunggu Validasi)</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="mt-2 inline-flex items-center text-xs font-medium text-amber-600">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Belum diisi
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex-shrink-0 flex items-center">
                                @if(!$isFilled)
                                    <a href="{{ route('guru.jurnal.create', ['jadwal' => $jadwal->id, 'tanggal' => $tanggal]) }}" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm transition-colors text-center inline-flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Isi Jurnal
                                    </a>
                                @else
                                    <button disabled class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 text-gray-400 text-sm font-bold rounded-lg cursor-not-allowed text-center inline-flex items-center justify-center border border-gray-200">
                                        Sudah Diisi
                                    </button>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <h3 class="text-lg font-bold text-gray-700 mb-1">Tidak Ada Jadwal</h3>
                    <p class="text-gray-500">Anda tidak memiliki jadwal mengajar pada tanggal ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
