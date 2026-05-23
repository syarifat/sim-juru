<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Kelola Jadwal Pelajaran</h2>
                <p class="text-sm text-gray-500">Tentukan jadwal mengajar guru pada kelas tertentu.</p>
            </div>
            <div>
                <a href="{{ route('admin.jadwal.create') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Jadwal
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

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('admin.jadwal.index') }}" class="flex flex-wrap gap-3">
                <div class="w-full sm:flex-1 md:w-auto">
                    <select name="tahun_ajaran_id" class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500" onchange="this.form.submit()">
                        <option value="">Semua Tahun Ajaran</option>
                        @foreach($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" {{ $tahunAjaranId == $ta->id ? 'selected' : '' }}>{{ $ta->tahun }} - {{ $ta->semester }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:flex-1 md:w-auto">
                    <select name="kelas_id" class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500" onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>
                        @foreach($kelases as $kelas)
                            <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:flex-1 md:w-auto">
                    <select name="hari" class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500" onchange="this.form.submit()">
                        <option value="">Semua Hari</option>
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                            <option value="{{ $h }}" {{ $hari == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>
                <noscript><button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">Filter</button></noscript>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Hari & Jam</th>
                            <th class="px-6 py-4">Kelas</th>
                            <th class="px-6 py-4">Mata Pelajaran</th>
                            <th class="px-6 py-4">Guru Utama</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($jadwals as $jadwal)
                            @php
                                $waktuMulai = $jamPelajarans[$jadwal->jam_ke_mulai]->jam_mulai ?? null;
                                $waktuSelesai = $jamPelajarans[$jadwal->jam_ke_selesai]->jam_selesai ?? null;
                                $waktuMulaiStr = $waktuMulai ? \Carbon\Carbon::parse($waktuMulai)->format('H.i') : '-';
                                $waktuSelesaiStr = $waktuSelesai ? \Carbon\Carbon::parse($waktuSelesai)->format('H.i') : '-';
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $jadwal->hari }}</div>
                                    <div class="text-xs text-gray-500 mt-1">Jam ke-{{ $jadwal->jam_ke_mulai }} s/d {{ $jadwal->jam_ke_selesai }} <span class="font-semibold text-emerald-600 ml-1">({{ $waktuMulaiStr }} - {{ $waktuSelesaiStr }})</span></div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-emerald-600">{{ $jadwal->kelas->nama_kelas }}</td>
                                <td class="px-6 py-4">{{ $jadwal->mataPelajaran->nama_mapel }}</td>
                                <td class="px-6 py-4">{{ $jadwal->guru->nama_lengkap }}</td>
                                <td class="px-6 py-4 text-right flex justify-end space-x-3 items-center">
                                    <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="text-amber-600 hover:text-amber-700 font-medium text-sm">Edit</a>
                                    <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400">Data jadwal belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="block md:hidden divide-y divide-gray-100">
                @forelse($jadwals as $jadwal)
                    @php
                        $waktuMulai = $jamPelajarans[$jadwal->jam_ke_mulai]->jam_mulai ?? null;
                        $waktuSelesai = $jamPelajarans[$jadwal->jam_ke_selesai]->jam_selesai ?? null;
                        $waktuMulaiStr = $waktuMulai ? \Carbon\Carbon::parse($waktuMulai)->format('H.i') : '-';
                        $waktuSelesaiStr = $waktuSelesai ? \Carbon\Carbon::parse($waktuSelesai)->format('H.i') : '-';
                    @endphp
                    <div class="p-3 bg-white hover:bg-emerald-50/20 transition-colors flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $jadwal->hari }}</span>
                                <span class="text-xs font-bold text-gray-500">Jam {{ $jadwal->jam_ke_mulai }}-{{ $jadwal->jam_ke_selesai }} <span class="text-emerald-600 font-medium">({{ $waktuMulaiStr }}-{{ $waktuSelesaiStr }})</span></span>
                            </div>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50/50 px-2 py-0.5 rounded">{{ $jadwal->kelas->nama_kelas }}</span>
                        </div>
                        <div class="text-xs text-gray-700">
                            <div class="font-bold text-gray-800">{{ $jadwal->mataPelajaran->nama_mapel }}</div>
                            <div class="text-gray-500 text-[11px] mt-0.5">Guru: <span class="font-medium text-gray-700">{{ $jadwal->guru->nama_lengkap }}</span></div>
                        </div>
                        <div class="flex items-center justify-between pt-1.5 border-t border-gray-50 text-xs">
                            <span class="text-gray-400 font-medium">Aksi</span>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="text-amber-600 hover:text-amber-700 font-semibold">Edit</a>
                                <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Yakin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-semibold">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-400 text-sm">Data jadwal belum tersedia.</div>
                @endforelse
            </div>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $jadwals->appends(['tahun_ajaran_id' => $tahunAjaranId, 'kelas_id' => $kelasId, 'hari' => $hari])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
