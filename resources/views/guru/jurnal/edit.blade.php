<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('guru.jurnal.riwayat') }}" class="p-2 text-gray-500 hover:text-gray-700 bg-white rounded-lg shadow-sm border border-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Revisi Jurnal Mengajar</h2>
                <p class="text-sm text-gray-500">Perbaiki materi dan catatan kegiatan belajar mengajar Anda.</p>
            </div>
        </div>

        @if($jurnal->catatan_kepsek)
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Catatan Kepala Sekolah:</h3>
                        <div class="mt-1 text-sm text-red-700 font-medium italic">
                            "{{ $jurnal->catatan_kepsek }}"
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-emerald-50 border-b border-emerald-100 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Jadwal Kelas</div>
                    <h3 class="text-2xl font-black text-gray-800">{{ $jadwal->kelas->nama_kelas }}</h3>
                    <p class="text-gray-600 font-medium">{{ $jadwal->mataPelajaran->nama_mapel }}</p>
                </div>
                <div class="bg-white px-4 py-3 rounded-lg border border-emerald-100 shadow-sm min-w-[200px]">
                    <div class="text-xs text-gray-500 mb-1 font-semibold">Waktu Pelaksanaan</div>
                    @php
                        $waktuMulai = $jamPelajarans[$jadwal->jam_ke_mulai]->jam_mulai ?? null;
                        $waktuSelesai = $jamPelajarans[$jadwal->jam_ke_selesai]->jam_selesai ?? null;
                        $waktuMulaiStr = $waktuMulai ? \Carbon\Carbon::parse($waktuMulai)->format('H.i') : '-';
                        $waktuSelesaiStr = $waktuSelesai ? \Carbon\Carbon::parse($waktuSelesai)->format('H.i') : '-';
                    @endphp
                    <div class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d M Y') }}</div>
                    <div class="text-emerald-600 font-bold mt-1 text-sm">Jam ke-{{ $jadwal->jam_ke_mulai }} s/d {{ $jadwal->jam_ke_selesai }} ({{ $waktuMulaiStr }} - {{ $waktuSelesaiStr }})</div>
                </div>
            </div>

            <form action="{{ route('guru.jurnal.update', $jurnal) }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="materi_pembelajaran" class="block text-sm font-bold text-gray-700 mb-2">Materi Pembelajaran <span class="text-red-500">*</span></label>
                    <textarea name="materi_pembelajaran" id="materi_pembelajaran" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="Tuliskan materi yang diajarkan hari ini secara rinci..." required>{{ old('materi_pembelajaran', $jurnal->materi_pembelajaran) }}</textarea>
                    @error('materi_pembelajaran')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="catatan_tambahan" class="block text-sm font-bold text-gray-700 mb-2">Catatan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea name="catatan_tambahan" id="catatan_tambahan" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" placeholder="Misal: Ada 2 siswa yang izin, atau PR di halaman 40...">{{ old('catatan_tambahan', $jurnal->catatan_tambahan) }}</textarea>
                    @error('catatan_tambahan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-6 border-t border-gray-100 flex justify-end space-x-3">
                    <a href="{{ route('guru.jurnal.riwayat') }}" class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
