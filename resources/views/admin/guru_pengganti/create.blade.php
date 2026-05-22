<x-app-layout>
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.guru-pengganti.index', ['tanggal' => $tanggal]) }}" class="p-2 text-gray-500 hover:text-gray-700 bg-white rounded-lg shadow-sm border border-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Atur Guru Pengganti</h2>
                <p class="text-sm text-gray-500">Pilih tanggal dan kelas untuk melihat jadwal dan mengatur guru pengganti.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <form method="GET" action="{{ route('admin.guru-pengganti.create') }}" class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Mengajar <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow" required>
                    </div>
                    <div>
                        <label for="kelas_id" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Kelas <span class="text-red-500">*</span></label>
                        <select name="kelas_id" id="kelas_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelases as $kelas)
                                <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-lg shadow-sm transition-colors">
                            Tampilkan Jadwal
                        </button>
                    </div>
                </div>
            </form>

            @if($kelasId)
                @if($jadwals->isEmpty())
                    <div class="text-center p-10 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-gray-500 font-medium">Tidak ada jadwal pelajaran untuk kelas ini pada hari <strong>{{ $namaHari }}</strong>.</p>
                    </div>
                @else
                    <form action="{{ route('admin.guru-pengganti.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                        <input type="hidden" name="kelas_id" value="{{ $kelasId }}">

                        <div class="mb-4 p-3 bg-blue-50 text-blue-700 rounded-lg text-sm border border-blue-100 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Jadwal Hari <strong>{{ $namaHari }}</strong>, Tanggal <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</strong>
                        </div>

                        <div class="space-y-4">
                            @foreach($jadwals as $jadwal)
                                @php
                                    $waktuMulai = $jamPelajarans[$jadwal->jam_ke_mulai]->jam_mulai ?? null;
                                    $waktuSelesai = $jamPelajarans[$jadwal->jam_ke_selesai]->jam_selesai ?? null;
                                    $waktuMulaiStr = $waktuMulai ? \Carbon\Carbon::parse($waktuMulai)->format('H.i') : '-';
                                    $waktuSelesaiStr = $waktuSelesai ? \Carbon\Carbon::parse($waktuSelesai)->format('H.i') : '-';
                                    
                                    // Cek apakah sudah ada guru pengganti yang diset
                                    $penggantiId = $jadwal->guruPenggantis->first()->guru_pengganti_id ?? null;
                                @endphp
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 p-4 border {{ $penggantiId ? 'border-amber-300 bg-amber-50' : 'border-gray-200 bg-white' }} rounded-xl items-center transition-colors">
                                    <div class="md:col-span-2">
                                        <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Jam Ke-{{ $jadwal->jam_ke_mulai }} s/d {{ $jadwal->jam_ke_selesai }}</div>
                                        <div class="font-bold text-gray-800">{{ $waktuMulaiStr }} - {{ $waktuSelesaiStr }}</div>
                                    </div>
                                    <div class="md:col-span-3">
                                        <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Mata Pelajaran</div>
                                        <div class="font-bold text-gray-800">{{ $jadwal->mataPelajaran->nama_mapel }}</div>
                                    </div>
                                    <div class="md:col-span-3">
                                        <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Guru Asli</div>
                                        <div class="font-bold text-gray-800">{{ $jadwal->guru->nama_lengkap }}</div>
                                    </div>
                                    <div class="md:col-span-4">
                                        <label class="block text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Pilih Guru Pengganti</label>
                                        <select name="penggantis[{{ $jadwal->id }}][guru_pengganti_id]" class="w-full px-3 py-2 text-sm border {{ $penggantiId ? 'border-amber-400 focus:ring-amber-500' : 'border-gray-300 focus:ring-blue-500' }} rounded-lg focus:ring-2 focus:outline-none transition-shadow">
                                            <option value="">-- Tetap Guru Asli --</option>
                                            @foreach($gurus as $guru)
                                                @if($guru->id !== $jadwal->guru_id)
                                                    <option value="{{ $guru->id }}" {{ $penggantiId == $guru->id ? 'selected' : '' }}>
                                                        {{ $guru->nama_lengkap }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-6 mt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.guru-pengganti.index', ['tanggal' => $tanggal]) }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition-colors">
                                Simpan Perubahan Pengganti
                            </button>
                        </div>
                    </form>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
