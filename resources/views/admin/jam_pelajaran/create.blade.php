<x-app-layout>
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.jam-pelajaran.index') }}" class="p-2 text-gray-500 hover:text-gray-700 bg-white rounded-lg shadow-sm border border-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Tambah Jam Pelajaran</h2>
                <p class="text-sm text-gray-500">Pilih hari dan tambahkan jam pelajaran / istirahat secara berurutan.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" 
             x-data="{
                rows: [ { jam_ke: 1, jam_mulai: '', jam_selesai: '', keterangan: '' } ],
                addRow() {
                    let nextJamKe = 1;
                    if (this.rows.length > 0) {
                        let lastVal = parseInt(this.rows[this.rows.length - 1].jam_ke);
                        nextJamKe = isNaN(lastVal) ? 1 : lastVal + 1;
                    }
                    this.rows.push({ jam_ke: nextJamKe, jam_mulai: '', jam_selesai: '', keterangan: '' });
                },
                removeRow(index) {
                    this.rows.splice(index, 1);
                }
             }">
             
            <form action="{{ route('admin.jam-pelajaran.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf
                
                @if ($errors->any())
                    <div class="p-4 bg-red-50 text-red-700 rounded-lg text-sm font-medium">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6">
                    <div class="w-full md:w-1/3">
                        <label for="hari" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Hari <span class="text-red-500">*</span></label>
                        <select name="hari" id="hari" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow" required>
                            <option value="">-- Pilih Hari --</option>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-md font-bold text-gray-800">Daftar Jam</h3>
                            <button type="button" @click="addRow()" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 font-semibold rounded-lg text-sm transition-colors border border-emerald-200">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Baris
                            </button>
                        </div>
                        
                        <div class="hidden md:grid grid-cols-12 gap-3 mb-2 px-2">
                            <div class="col-span-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Ke- <span class="text-red-500">*</span></div>
                            <div class="col-span-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mulai <span class="text-red-500">*</span></div>
                            <div class="col-span-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Selesai <span class="text-red-500">*</span></div>
                            <div class="col-span-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</div>
                            <div class="col-span-1 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</div>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(row, index) in rows" :key="index">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 p-3 md:p-0 border border-gray-200 md:border-transparent rounded-lg bg-gray-50 md:bg-transparent items-center">
                                    
                                    <div class="md:col-span-2 flex flex-col md:block">
                                        <label class="md:hidden text-xs font-semibold text-gray-500 uppercase mb-1">Jam Ke-</label>
                                        <input type="number" x-bind:name="`jam[${index}][jam_ke]`" x-model="row.jam_ke" min="0" placeholder="0 = Istirahat" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                    </div>
                                    
                                    <div class="md:col-span-3 flex flex-col md:block">
                                        <label class="md:hidden text-xs font-semibold text-gray-500 uppercase mb-1">Mulai</label>
                                        <input type="time" x-bind:name="`jam[${index}][jam_mulai]`" x-model="row.jam_mulai" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                    </div>
                                    
                                    <div class="md:col-span-3 flex flex-col md:block">
                                        <label class="md:hidden text-xs font-semibold text-gray-500 uppercase mb-1">Selesai</label>
                                        <input type="time" x-bind:name="`jam[${index}][jam_selesai]`" x-model="row.jam_selesai" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                    </div>
                                    
                                    <div class="md:col-span-3 flex flex-col md:block">
                                        <label class="md:hidden text-xs font-semibold text-gray-500 uppercase mb-1">Keterangan</label>
                                        <input type="text" x-bind:name="`jam[${index}][keterangan]`" x-model="row.keterangan" placeholder="Isi jika perlu..." class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    
                                    <div class="md:col-span-1 text-right md:text-center mt-2 md:mt-0">
                                        <button type="button" @click="removeRow(index)" class="inline-flex items-center justify-center p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Baris">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <span class="md:hidden ml-1 text-sm font-medium">Hapus</span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.jam-pelajaran.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition-colors">
                        Simpan Semua Jam
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
