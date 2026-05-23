<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('kepsek.validasi.index', ['tanggal' => $jurnal->tanggal_mengajar]) }}" class="p-2 text-gray-500 hover:text-gray-700 bg-white rounded-lg shadow-sm border border-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Detail & Validasi Jurnal</h2>
                <p class="text-sm text-gray-500">Periksa materi yang diajarkan dan berikan validasi.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Kolom Detail Jurnal -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-emerald-50 border-b border-emerald-100 p-6 flex justify-between items-center">
                        <div>
                            <div class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Informasi Jurnal</div>
                            <h3 class="text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($jurnal->tanggal_mengajar)->translatedFormat('l, d F Y') }}</h3>
                        </div>
                        <div class="text-right">
                            @if($jurnal->status_validasi === 'Disetujui')
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-bold">Disetujui</span>
                            @elseif($jurnal->status_validasi === 'Revisi')
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-bold">Revisi</span>
                            @else
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-bold">Pending</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4 pb-4 border-b border-gray-100">
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Guru Pengajar</p>
                                <p class="font-medium text-gray-900">{{ $jurnal->guruPengisi->nama_lengkap }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Kelas & Mapel</p>
                                <p class="font-medium text-gray-900">{{ $jurnal->jadwal->kelas->nama_kelas }} - {{ $jurnal->jadwal->mataPelajaran->nama_mapel }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Materi Pembelajaran</p>
                            <div class="p-4 bg-gray-50 rounded-lg text-gray-800 border border-gray-100 whitespace-pre-line">{{ $jurnal->materi_pembelajaran }}</div>
                        </div>

                        @if($jurnal->catatan_tambahan)
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Catatan Tambahan Guru</p>
                                <div class="p-4 bg-amber-50 rounded-lg text-amber-900 border border-amber-100 whitespace-pre-line italic">{{ $jurnal->catatan_tambahan }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kolom Form Validasi -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Validasi</h3>
                    
                    <form action="{{ route('kepsek.validasi.update', $jurnal) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status Validasi <span class="text-red-500">*</span></label>
                            <div class="space-y-2">
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ $jurnal->status_validasi === 'Disetujui' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200' }}">
                                    <input type="radio" name="status_validasi" value="Disetujui" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500" {{ $jurnal->status_validasi === 'Disetujui' ? 'checked' : '' }} required>
                                    <span class="ml-3 font-semibold text-gray-700">Disetujui</span>
                                </label>
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ $jurnal->status_validasi === 'Revisi' ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                                    <input type="radio" name="status_validasi" value="Revisi" class="w-4 h-4 text-red-600 focus:ring-red-500" {{ $jurnal->status_validasi === 'Revisi' ? 'checked' : '' }} required>
                                    <span class="ml-3 font-semibold text-gray-700">Revisi</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="catatan_kepsek" class="block text-sm font-bold text-gray-700 mb-2">Catatan Kepala Sekolah</label>
                            <textarea name="catatan_kepsek" id="catatan_kepsek" rows="4" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-sm" placeholder="Berikan catatan jika ada perbaikan atau apresiasi...">{{ old('catatan_kepsek', $jurnal->catatan_kepsek) }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-sm transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Validasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
