<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Riwayat Jurnal</h2>
                <p class="text-sm text-gray-500">Lihat rekam jejak jurnal mengajar Anda dan status validasinya.</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('guru.jurnal.riwayat') }}" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Bulan</label>
                    <select name="bulan" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500" onchange="this.form.submit()">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tahun</label>
                    <select name="tahun" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500" onchange="this.form.submit()">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4 whitespace-nowrap">Tanggal</th>
                            <th class="px-6 py-4">Kelas & Mapel</th>
                            <th class="px-6 py-4">Jam Ke-</th>
                            <th class="px-6 py-4 w-1/3">Materi & Catatan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($jurnals as $jurnal)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal_mengajar)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-emerald-600">{{ $jurnal->jadwal->kelas->nama_kelas }}</div>
                                    <div class="text-xs text-gray-500">{{ $jurnal->jadwal->mataPelajaran->nama_mapel }}</div>
                                </td>
                                <td class="px-6 py-4 text-center font-bold">
                                    {{ $jurnal->jadwal->jam_ke_mulai }} - {{ $jurnal->jadwal->jam_ke_selesai }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800">{{ $jurnal->materi_pembelajaran }}</div>
                                    @if($jurnal->catatan_tambahan)
                                        <div class="text-xs text-gray-500 mt-1 italic">Catatan: {{ $jurnal->catatan_tambahan }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-center">
                                        @if($jurnal->status_validasi === 'Disetujui')
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold w-full text-center">Disetujui</span>
                                        @elseif($jurnal->status_validasi === 'Revisi')
                                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold w-full text-center">Revisi</span>
                                        @else
                                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold w-full text-center">Pending</span>
                                        @endif
                                        
                                        @if($jurnal->catatan_kepsek)
                                            <div class="mt-2 w-full text-xs p-2 bg-gray-50 border border-gray-200 rounded text-gray-600 italic">
                                                <strong>Kepsek:</strong> {{ $jurnal->catatan_kepsek }}
                                            </div>
                                        @endif

                                        @if($jurnal->status_validasi === 'Revisi')
                                            <a href="{{ route('guru.jurnal.edit', $jurnal) }}" class="mt-2 w-full text-center px-3 py-1 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 border border-emerald-200 rounded text-xs font-bold transition-colors">
                                                Perbaiki Jurnal
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-gray-500 font-medium">Anda belum memiliki riwayat jurnal pada bulan ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
