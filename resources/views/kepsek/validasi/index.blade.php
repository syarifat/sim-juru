<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Validasi Jurnal</h2>
                <p class="text-sm text-gray-500">Periksa dan berikan validasi pada jurnal harian guru.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg text-sm font-medium shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('kepsek.validasi.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:w-1/3">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="w-full sm:w-1/3">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Guru</label>
                    <select name="guru_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Guru</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ $guruId == $guru->id ? 'selected' : '' }}>{{ $guru->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-auto">
                    <button type="submit" class="w-full px-6 py-2 bg-blue-50 text-blue-700 font-bold rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Guru</th>
                            <th class="px-6 py-4">Kelas & Mapel</th>
                            <th class="px-6 py-4">Jam Ke-</th>
                            <th class="px-6 py-4">Status Validasi</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($jurnals as $jurnal)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $jurnal->guruPengisi->nama_lengkap }}</div>
                                    @if($jurnal->guru_pengisi_id !== $jurnal->jadwal->guru_id)
                                        <span class="inline-block mt-1 text-[10px] px-2 py-0.5 bg-amber-100 text-amber-800 rounded font-bold">Pengganti</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-blue-600">{{ $jurnal->jadwal->kelas->nama_kelas }}</div>
                                    <div class="text-xs text-gray-500">{{ $jurnal->jadwal->mataPelajaran->nama_mapel }}</div>
                                </td>
                                <td class="px-6 py-4 font-bold text-center">
                                    {{ $jurnal->jadwal->jam_ke_mulai }} - {{ $jurnal->jadwal->jam_ke_selesai }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($jurnal->status_validasi === 'Disetujui')
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Disetujui</span>
                                    @elseif($jurnal->status_validasi === 'Revisi')
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Revisi</span>
                                    @else
                                        <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('kepsek.validasi.edit', $jurnal) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 font-semibold rounded text-xs transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        {{ $jurnal->status_validasi === 'Pending' ? 'Validasi' : 'Lihat / Ubah' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-gray-500 font-medium">Tidak ada jurnal untuk kriteria yang dipilih.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
