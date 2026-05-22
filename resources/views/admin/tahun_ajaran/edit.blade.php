<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.tahun-ajaran.index') }}" class="p-2 text-gray-500 hover:text-gray-700 bg-white rounded-lg shadow-sm border border-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Edit Tahun Ajaran</h2>
                <p class="text-sm text-gray-500">Perbarui informasi tahun ajaran dan status aktifnya.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.tahun-ajaran.update', $tahunAjaran->id) }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label for="tahun" class="block text-sm font-semibold text-gray-700 mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                        <input type="text" name="tahun" id="tahun" value="{{ old('tahun', $tahunAjaran->tahun) }}" placeholder="Contoh: 2023/2024" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow @error('tahun') border-red-500 @enderror" required>
                        @error('tahun')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-semibold text-gray-700 mb-1">Semester <span class="text-red-500">*</span></label>
                        <select name="semester" id="semester" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow @error('semester') border-red-500 @enderror" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="Ganjil" {{ old('semester', $tahunAjaran->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ old('semester', $tahunAjaran->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                        @error('semester')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status_aktif" class="block text-sm font-semibold text-gray-700 mb-1">Status Aktif <span class="text-red-500">*</span></label>
                        <select name="status_aktif" id="status_aktif" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow @error('status_aktif') border-red-500 @enderror" required>
                            <option value="Tidak Aktif" {{ old('status_aktif', $tahunAjaran->status_aktif) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="Aktif" {{ old('status_aktif', $tahunAjaran->status_aktif) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Pilih 'Aktif' jika ini adalah tahun ajaran berjalan. Hanya boleh ada 1 tahun ajaran aktif.</p>
                        @error('status_aktif')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.tahun-ajaran.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition-colors">
                        Perbarui Tahun Ajaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
