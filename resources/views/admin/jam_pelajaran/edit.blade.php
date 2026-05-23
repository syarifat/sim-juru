<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.jam-pelajaran.index') }}" class="p-2 text-gray-500 hover:text-gray-700 bg-white rounded-lg shadow-sm border border-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Edit Jam Pelajaran</h2>
                <p class="text-sm text-gray-500">Perbarui jadwal dan waktu untuk jam pelajaran ini.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.jam-pelajaran.update', $jam_pelajaran->id) }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="hari" class="block text-sm font-semibold text-gray-700 mb-1">Hari <span class="text-red-500">*</span></label>
                            <select name="hari" id="hari" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-shadow @error('hari') border-red-500 @enderror" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                    <option value="{{ $hari }}" {{ old('hari', $jam_pelajaran->hari) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                @endforeach
                            </select>
                            @error('hari')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jam_ke" class="block text-sm font-semibold text-gray-700 mb-1">Jam Ke- <span class="text-red-500">*</span></label>
                            <input type="number" name="jam_ke" id="jam_ke" value="{{ old('jam_ke', $jam_pelajaran->jam_ke) }}" min="0" placeholder="Contoh: 1, 2, atau 0 (untuk Istirahat)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-shadow @error('jam_ke') border-red-500 @enderror" required>
                            <p class="mt-1 text-xs text-gray-500">Isi dengan 0 untuk waktu Istirahat.</p>
                            @error('jam_ke')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="jam_mulai" class="block text-sm font-semibold text-gray-700 mb-1">Jam Mulai <span class="text-red-500">*</span></label>
                            <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai', \Carbon\Carbon::parse($jam_pelajaran->jam_mulai)->format('H:i')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-shadow @error('jam_mulai') border-red-500 @enderror" required>
                            @error('jam_mulai')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jam_selesai" class="block text-sm font-semibold text-gray-700 mb-1">Jam Selesai <span class="text-red-500">*</span></label>
                            <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai', \Carbon\Carbon::parse($jam_pelajaran->jam_selesai)->format('H:i')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-shadow @error('jam_selesai') border-red-500 @enderror" required>
                            @error('jam_selesai')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-1">Keterangan (Opsional)</label>
                        <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan', $jam_pelajaran->keterangan) }}" placeholder="Contoh: Upacara, Istirahat Pertama, dll." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-shadow @error('keterangan') border-red-500 @enderror">
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.jam-pelajaran.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm transition-colors">
                        Perbarui Jam Pelajaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
