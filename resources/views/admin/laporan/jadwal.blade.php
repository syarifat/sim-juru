<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Laporan Jadwal Mengajar</h2>
                <p class="text-sm text-gray-500">Cetak rekapitulasi jadwal mengajar guru per individu atau seluruh guru sekaligus.</p>
            </div>
            <div>
                <button type="button"
                    onclick="document.getElementById('formAction').value='export_pdf'; document.getElementById('filterForm').submit();"
                    class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 bg-red-650 hover:bg-red-750 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export PDF
                </button>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('admin.laporan.jadwal.index') }}" id="filterForm" class="space-y-4">
                <input type="hidden" name="action" value="filter" id="formAction">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                            @foreach($tahunAjarans as $ta)
                                <option value="{{ $ta->id }}" {{ $tahunAjaranId == $ta->id ? 'selected' : '' }}>{{ $ta->tahun }} - {{ $ta->semester }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Guru Pengajar</label>
                        <select name="guru_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">-- Semua Guru --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ $guruId == $guru->id ? 'selected' : '' }}>{{ $guru->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="document.getElementById('formAction').value='filter'; document.getElementById('filterForm').submit();" class="w-full px-4 py-2 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200 hover:bg-emerald-100 transition-colors">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 shadow-sm text-xs font-semibold text-emerald-700">
            Menampilkan Jadwal untuk: 
            <span class="text-emerald-900 font-extrabold ml-1 uppercase">
                {{ $selectedGuru ? $selectedGuru->nama_lengkap : 'Semua Guru (Master Schedule)' }}
            </span>
            <span class="text-emerald-400 mx-2">|</span>
            Tahun Ajaran: 
            <span class="text-emerald-900 font-extrabold ml-1">
                {{ $selectedTahunAjaran->tahun }} ({{ $selectedTahunAjaran->semester }})
            </span>
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-yellow-400 text-slate-900 text-xs font-extrabold uppercase tracking-wider border-b border-gray-200">
                            <th class="px-4 py-3 text-center border border-gray-200" rowspan="2" style="width: 5%">NO</th>
                            <th class="px-4 py-3 border border-gray-200" rowspan="2" style="width: 15%">HARI</th>
                            @if(!$selectedGuru)
                                <th class="px-4 py-3 border border-gray-200" rowspan="2" style="width: 23%">NAMA GURU</th>
                            @endif
                            <th class="px-4 py-3 border border-gray-200" rowspan="2" style="width: {{ $selectedGuru ? '35%' : '25%' }}">MATA PELAJARAN</th>
                            <th class="px-4 py-3 border border-gray-200" rowspan="2" style="width: {{ $selectedGuru ? '15%' : '10%' }}">KELAS</th>
                            <th class="px-4 py-2 text-center border border-gray-200" colspan="2" style="width: {{ $selectedGuru ? '25%' : '20%' }}">JAM PELAJARAN</th>
                            <th class="px-4 py-3 border border-gray-200" rowspan="2" style="width: 10%">KET</th>
                        </tr>
                        <tr class="bg-yellow-200 text-slate-800 text-xs font-bold uppercase tracking-wider border-b border-gray-200">
                            <th class="px-2 py-2 text-center text-white bg-emerald-600 border border-emerald-700">MULAI</th>
                            <th class="px-2 py-2 text-center text-white bg-rose-600 border border-rose-700">SELESAI</th>
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
                                @if(!$selectedGuru)
                                    <td class="px-4 py-3 font-bold text-gray-900 border border-gray-200">
                                        {{ $jadwal->guru->nama_lengkap }}
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
                                <td colspan="{{ $selectedGuru ? 5 : 6 }}" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="font-medium">Tidak ada jadwal mengajar yang terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile Card View --}}
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
                    @if(!$selectedGuru)
                        <div class="text-xs font-bold text-gray-800">{{ $jadwal->guru->nama_lengkap }}</div>
                    @endif
                    <div class="flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-gray-850">{{ $jadwal->kelas->nama_kelas }}</span>
                            <span class="text-gray-400 mx-1.5">&bull;</span>
                            <span class="font-medium text-gray-700">{{ $jadwal->mataPelajaran->nama_mapel }}</span>
                        </div>
                        <span class="text-[11px] font-mono text-gray-500 font-semibold bg-gray-50 border border-gray-100 px-1.5 py-0.5 rounded">{{ $waktuMulaiStr }} - {{ $waktuSelesaiStr }}</span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400 text-sm">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p class="font-medium">Tidak ada jadwal mengajar.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
