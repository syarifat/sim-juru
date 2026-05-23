<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Laporan Jurnal Mengajar</h2>
                <p class="text-sm text-gray-500">Rekapitulasi aktivitas mengajar guru berdasarkan filter tertentu.</p>
            </div>
            <div>
                <button type="button"
                    onclick="document.getElementById('formAction').value='export_pdf'; document.getElementById('filterForm').submit();"
                    class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export PDF
                </button>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('admin.laporan.index') }}" id="filterForm" class="space-y-4">
                <input type="hidden" name="action" value="filter" id="formAction">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Tahun Ajaran</option>
                            @foreach($tahunAjarans as $ta)
                                <option value="{{ $ta->id }}" {{ $tahunAjaranId == $ta->id ? 'selected' : '' }}>{{ $ta->tahun }} - {{ $ta->semester }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Validasi</label>
                        <select name="status_validasi" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Status</option>
                            <option value="Pending" {{ $statusValidasi == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Disetujui" {{ $statusValidasi == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Revisi" {{ $statusValidasi == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                        </select>
                    </div>
                    <div class="lg:col-span-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Guru</label>
                        <select name="guru_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Guru</option>
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

        {{-- Desktop Table --}}
        <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-4 py-3 whitespace-nowrap">Tanggal</th>
                            <th class="px-4 py-3">Guru Pengisi</th>
                            <th class="px-4 py-3">Kelas & Mapel</th>
                            <th class="px-4 py-3">Jam Ke-</th>
                            <th class="px-4 py-3">Materi</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($jurnals as $jurnal)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal_mengajar)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-800">{{ $jurnal->guruPengisi->nama_lengkap }}</div>
                                    @if($jurnal->guru_pengisi_id !== $jurnal->jadwal->guru_id)
                                        <span class="inline-block mt-1 text-[10px] px-2 py-0.5 bg-amber-100 text-amber-800 rounded font-bold">Pengganti</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-emerald-600">{{ $jurnal->jadwal->kelas->nama_kelas }}</div>
                                    <div class="text-xs text-gray-500">{{ $jurnal->jadwal->mataPelajaran->nama_mapel }}</div>
                                </td>
                                <td class="px-4 py-3 text-center font-bold">
                                    {{ $jurnal->jadwal->jam_ke_mulai }} - {{ $jurnal->jadwal->jam_ke_selesai }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="max-w-xs truncate" title="{{ $jurnal->materi_pembelajaran }}">
                                        {{ Str::limit($jurnal->materi_pembelajaran, 40) }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($jurnal->status_validasi === 'Disetujui')
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Disetujui</span>
                                    @elseif($jurnal->status_validasi === 'Revisi')
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Revisi</span>
                                    @else
                                        <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-gray-500 font-medium">Belum ada data jurnal untuk kriteria yang dipilih.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile Card View --}}
        <div class="md:hidden divide-y divide-gray-100 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @forelse($jurnals as $jurnal)
                <div class="p-3 hover:bg-emerald-50/20 transition-colors flex flex-col gap-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50/80 px-2 py-0.5 rounded">{{ \Carbon\Carbon::parse($jurnal->tanggal_mengajar)->translatedFormat('d M Y') }}</span>
                        @if($jurnal->status_validasi === 'Disetujui')
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-[10px] font-extrabold uppercase tracking-wider">Disetujui</span>
                        @elseif($jurnal->status_validasi === 'Revisi')
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-[10px] font-extrabold uppercase tracking-wider">Revisi</span>
                        @else
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded text-[10px] font-extrabold uppercase tracking-wider">Pending</span>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <span>{{ $jurnal->guruPengisi->nama_lengkap }}</span>
                            @if($jurnal->guru_pengisi_id !== $jurnal->jadwal->guru_id)
                                <span class="text-[9px] px-1.5 py-0.2 bg-amber-100 text-amber-800 rounded font-extrabold uppercase tracking-wider">Inval</span>
                            @endif
                        </div>
                        <div class="text-[11px] text-gray-500 mt-0.5">
                            <span class="font-bold text-emerald-600">{{ $jurnal->jadwal->kelas->nama_kelas }}</span> &bull; 
                            <span class="font-medium text-gray-700">{{ $jurnal->jadwal->mataPelajaran->nama_mapel }}</span> &bull; 
                            <span>Jam {{ $jurnal->jadwal->jam_ke_mulai }}-{{ $jurnal->jadwal->jam_ke_selesai }}</span>
                        </div>
                    </div>
                    <div class="text-[11px] text-gray-600 bg-gray-50 p-2 rounded border border-gray-100 leading-relaxed font-medium">
                        {{ Str::limit($jurnal->materi_pembelajaran, 120) }}
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-400 text-sm">Belum ada data jurnal.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
