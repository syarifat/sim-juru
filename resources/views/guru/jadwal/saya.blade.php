<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Jadwal Mengajar Saya</h2>
                <p class="text-sm text-gray-500">Jadwal mengajar rutin Anda selama seminggu pada tahun ajaran aktif.</p>
            </div>
            <div>
                <a href="{{ route('guru.jadwal.saya', ['action' => 'export_pdf']) }}" 
                   class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 bg-red-650 hover:bg-red-750 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Unduh PDF
                </a>
            </div>
        </div>

        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center text-white text-sm font-bold mr-3 flex-shrink-0">
                    {{ strtoupper(substr($guru->nama_lengkap, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-sm font-bold text-emerald-900 leading-none">{{ $guru->nama_lengkap }}</h3>
                    <p class="text-xs text-emerald-700 mt-1">NIP: <span class="font-mono">{{ $guru->nip ?? '-' }}</span></p>
                </div>
            </div>
            <div class="text-xs font-semibold text-emerald-700 bg-white/80 border border-emerald-200 px-3 py-1.5 rounded-lg flex-shrink-0">
                Tahun Ajaran: <span class="text-emerald-900 font-bold ml-1">{{ $activeTahunAjaran->tahun }} ({{ $activeTahunAjaran->semester }})</span>
            </div>
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-yellow-400 text-slate-900 text-xs font-extrabold uppercase tracking-wider border-b border-gray-200">
                            <th class="px-4 py-3 text-center border border-gray-200" rowspan="2" style="width: 5%">NO</th>
                            <th class="px-4 py-3 border border-gray-200" rowspan="2" style="width: 15%">HARI</th>
                            <th class="px-4 py-3 border border-gray-200" rowspan="2" style="width: 30%">MATA PELAJARAN</th>
                            <th class="px-4 py-3 border border-gray-200" rowspan="2" style="width: 15%">KELAS</th>
                            <th class="px-4 py-2 text-center border border-gray-200" colspan="2" style="width: 25%">JAM PELAJARAN</th>
                            <th class="px-4 py-3 border border-gray-200" rowspan="2" style="width: 10%">KET</th>
                        </tr>
                        <tr class="bg-yellow-200 text-slate-800 text-xs font-bold uppercase tracking-wider border-b border-gray-200">
                            <th class="px-2 py-2 text-center text-white bg-emerald-600 border border-emerald-700" style="width: 12.5%">MULAI</th>
                            <th class="px-2 py-2 text-center text-white bg-rose-600 border border-rose-700" style="width: 12.5%">SELESAI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                        @php
                            $currentHari = '';
                            $dayCounter = 0;
                        @endphp
                        @forelse($jadwals as $index => $jadwal)
                            @php
                                $waktuMulai = $jamPelajarans[$jadwal->jam_ke_mulai]->jam_mulai ?? null;
                                $waktuSelesai = $jamPelajarans[$jadwal->jam_ke_selesai]->jam_selesai ?? null;
                                $waktuMulaiStr = $waktuMulai ? \Carbon\Carbon::parse($waktuMulai)->format('H:i') : '-';
                                $waktuSelesaiStr = $waktuSelesai ? \Carbon\Carbon::parse($waktuSelesai)->format('H:i') : '-';
                                $isHariChanged = $currentHari !== $jadwal->hari;
                                if ($isHariChanged) {
                                    $currentHari = $jadwal->hari;
                                    $dayCounter = 1;
                                } else {
                                    $dayCounter++;
                                }
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 py-3 text-center font-bold text-gray-500 border border-gray-200 bg-gray-50/50">
                                    {{ $dayCounter }}
                                </td>
                                @if($isHariChanged)
                                    @php
                                        $hariCount = $jadwals->where('hari', $jadwal->hari)->count();
                                    @endphp
                                    <td class="px-4 py-3 font-extrabold text-slate-800 bg-slate-100/80 border border-gray-200 vertical-middle align-top uppercase" rowspan="{{ $hariCount }}">
                                        {{ $jadwal->hari }}
                                    </td>
                                @endif
                                <td class="px-4 py-3 font-semibold text-gray-900 border border-gray-200">
                                    {{ $jadwal->mataPelajaran->nama_mapel }}
                                </td>
                                <td class="px-4 py-3 font-bold text-emerald-700 border border-gray-200">
                                    {{ $jadwal->kelas->nama_kelas }}
                                </td>
                                <td class="px-4 py-3 text-center font-mono font-bold text-emerald-600 bg-emerald-50/30 border border-gray-200">
                                    {{ $waktuMulaiStr }}
                                </td>
                                <td class="px-4 py-3 text-center font-mono font-bold text-rose-600 bg-rose-50/30 border border-gray-200">
                                    {{ $waktuSelesaiStr }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500 font-medium border border-gray-200">
                                    Jam Ke {{ $jadwal->jam_ke_mulai }}{{ $jadwal->jam_ke_mulai != $jadwal->jam_ke_selesai ? '-' . $jadwal->jam_ke_selesai : '' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400 border border-gray-200">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="font-medium">Belum ada jadwal mengajar yang terdaftar untuk Anda pada tahun ajaran ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile Card View (Optimized & Compact) --}}
        <div class="md:hidden divide-y divide-gray-100 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @forelse($jadwals as $jadwal)
                @php
                    $waktuMulai = $jamPelajarans[$jadwal->jam_ke_mulai]->jam_mulai ?? null;
                    $waktuSelesai = $jamPelajarans[$jadwal->jam_ke_selesai]->jam_selesai ?? null;
                    $waktuMulaiStr = $waktuMulai ? \Carbon\Carbon::parse($waktuMulai)->format('H:i') : '-';
                    $waktuSelesaiStr = $waktuSelesai ? \Carbon\Carbon::parse($waktuSelesai)->format('H:i') : '-';
                @endphp
                <div class="p-3 hover:bg-emerald-50/20 transition-colors flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-800 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded">{{ $jadwal->hari }}</span>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded">Jam {{ $jadwal->jam_ke_mulai }} - {{ $jadwal->jam_ke_selesai }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-gray-800">{{ $jadwal->kelas->nama_kelas }}</span>
                            <span class="text-gray-400 mx-1.5">&bull;</span>
                            <span class="font-medium text-gray-700">{{ $jadwal->mataPelajaran->nama_mapel }}</span>
                        </div>
                        <span class="text-[11px] font-mono text-gray-500 font-semibold bg-gray-50 border border-gray-100 px-1.5 py-0.5 rounded">{{ $waktuMulaiStr }} - {{ $waktuSelesaiStr }}</span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400 text-sm">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p class="font-medium">Belum ada jadwal mengajar.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
