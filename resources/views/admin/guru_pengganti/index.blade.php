<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Guru Pengganti</h2>
                <p class="text-sm text-gray-500">Kelola daftar guru pengganti (inval) pada tanggal tertentu.</p>
            </div>
            <div>
                <a href="{{ route('admin.guru-pengganti.create', ['tanggal' => $tanggal]) }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Atur Guru Pengganti
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <form method="GET" action="{{ route('admin.guru-pengganti.index') }}" class="flex items-center space-x-3">
                <label for="tanggal" class="text-sm font-bold text-gray-700">Pilih Tanggal:</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}" class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500" onchange="this.form.submit()">
            </form>
            <div class="text-sm text-gray-500 hidden sm:block">
                Menampilkan data untuk tanggal: <span class="font-bold text-emerald-600">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Kelas & Jam</th>
                            <th class="px-6 py-4">Mata Pelajaran</th>
                            <th class="px-6 py-4">Guru Asli</th>
                            <th class="px-6 py-4">Guru Pengganti</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($penggantis as $pengganti)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $pengganti->jadwal->kelas->nama_kelas }}</div>
                                    <div class="text-xs text-gray-500 mt-1">Jam ke-{{ $pengganti->jadwal->jam_ke_mulai }} s/d {{ $pengganti->jadwal->jam_ke_selesai }}</div>
                                </td>
                                <td class="px-6 py-4 font-medium">{{ $pengganti->jadwal->mataPelajaran->nama_mapel }}</td>
                                <td class="px-6 py-4 text-red-600 font-semibold line-through decoration-red-300">{{ $pengganti->jadwal->guru->nama_lengkap }}</td>
                                <td class="px-6 py-4 text-emerald-600 font-bold flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    {{ $pengganti->guruPengganti->nama_lengkap }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.guru-pengganti.destroy', $pengganti->id) }}" method="POST" onsubmit="return confirm('Batalkan guru pengganti ini? (Akan kembali ke guru asli)')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm">Batal</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Tidak ada guru pengganti yang ditugaskan pada tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="block md:hidden divide-y divide-gray-100">
                <div class="p-2.5 bg-gray-50 text-xs text-center text-gray-500 border-b border-gray-100">
                    Data tanggal: <span class="font-bold text-emerald-600">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y') }}</span>
                </div>
                @forelse($penggantis as $pengganti)
                    <div class="p-3 bg-white hover:bg-emerald-50/20 transition-colors flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $pengganti->jadwal->kelas->nama_kelas }}</span>
                                <span class="text-xs text-gray-500 font-bold">Jam {{ $pengganti->jadwal->jam_ke_mulai }}-{{ $pengganti->jadwal->jam_ke_selesai }}</span>
                            </div>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50/50 px-2 py-0.5 rounded">{{ $pengganti->jadwal->mataPelajaran->nama_mapel }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-1.5 border-t border-gray-50 text-xs">
                            <div class="flex flex-col gap-0.5">
                                <div class="text-[10px] text-gray-400">Guru: <span class="text-red-500 line-through decoration-red-300 font-medium">{{ $pengganti->jadwal->guru->nama_lengkap }}</span></div>
                                <div class="text-xs font-bold text-emerald-700">Inval: {{ $pengganti->guruPengganti->nama_lengkap }}</div>
                            </div>
                            <form action="{{ route('admin.guru-pengganti.destroy', $pengganti->id) }}" method="POST" onsubmit="return confirm('Batalkan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 text-xs font-bold bg-red-50 text-red-600 rounded border border-red-100 hover:bg-red-100 transition-colors">Batal</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-400 text-sm">Tidak ada guru pengganti.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
