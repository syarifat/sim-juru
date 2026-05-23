<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6" x-data="{ 
        editKelasOpen: false, 
        editMapelOpen: false, 
        currentKelasId: null, 
        currentKelasNama: '',
        currentMapelId: null,
        currentMapelNama: ''
    }">
        
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <h2 class="text-xl font-bold text-gray-800">Data Master Akademik</h2>
            <p class="text-sm text-gray-500">Kelola daftar kelas dan mata pelajaran.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Kolom Kelas -->
            <div class="space-y-4">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Kelola Kelas</h3>
                    
                    @if(session('success_kelas'))
                        <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-medium">
                            {{ session('success_kelas') }}
                        </div>
                    @endif
                    @error('nama_kelas')
                        <div class="mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm font-medium">
                            {{ $message }}
                        </div>
                    @enderror

                    <form action="{{ route('admin.kelas.store') }}" method="POST" class="flex gap-2 mb-6">
                        @csrf
                        <input type="text" name="nama_kelas" placeholder="Tambah kelas baru..." class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                            Tambah
                        </button>
                    </form>

                    <div class="overflow-hidden border border-gray-100 rounded-lg">
                        <table class="w-full text-left text-sm text-gray-700">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3 font-bold">Nama Kelas</th>
                                    <th class="px-4 py-3 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($kelases as $kelas)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-4 py-3 font-medium">{{ $kelas->nama_kelas }}</td>
                                        <td class="px-4 py-3 text-right flex justify-end gap-3">
                                            <button type="button" @click="editKelasOpen = true; currentKelasId = '{{ $kelas->id }}'; currentKelasNama = '{{ $kelas->nama_kelas }}'" class="text-amber-600 hover:text-amber-700 font-medium">Edit</button>
                                            <form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-700 font-medium">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-gray-400">Belum ada data kelas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Kolom Mapel -->
            <div class="space-y-4">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Kelola Mata Pelajaran</h3>
                    
                    @if(session('success_mapel'))
                        <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-medium">
                            {{ session('success_mapel') }}
                        </div>
                    @endif
                    @error('nama_mapel')
                        <div class="mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm font-medium">
                            {{ $message }}
                        </div>
                    @enderror

                    <form action="{{ route('admin.mapel.store') }}" method="POST" class="flex gap-2 mb-6">
                        @csrf
                        <input type="text" name="nama_mapel" placeholder="Tambah mata pelajaran..." class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                            Tambah
                        </button>
                    </form>

                    <div class="overflow-hidden border border-gray-100 rounded-lg">
                        <table class="w-full text-left text-sm text-gray-700">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3 font-bold">Mata Pelajaran</th>
                                    <th class="px-4 py-3 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($mapels as $mapel)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-4 py-3 font-medium">{{ $mapel->nama_mapel }}</td>
                                        <td class="px-4 py-3 text-right flex justify-end gap-3">
                                            <button type="button" @click="editMapelOpen = true; currentMapelId = '{{ $mapel->id }}'; currentMapelNama = '{{ $mapel->nama_mapel }}'" class="text-amber-600 hover:text-amber-700 font-medium">Edit</button>
                                            <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-700 font-medium">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-gray-400">Belum ada data mata pelajaran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal Edit Kelas -->
        <div x-show="editKelasOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50" style="display: none;">
            <div @click.away="editKelasOpen = false" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 m-4">
                <div class="flex justify-between items-center mb-5 border-b pb-3">
                    <h3 class="text-lg font-bold text-gray-800">Edit Kelas</h3>
                    <button @click="editKelasOpen = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form :action="`{{ url('admin/kelas') }}/${currentKelasId}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Kelas</label>
                        <input type="text" name="nama_kelas" x-model="currentKelasNama" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" @click="editKelasOpen = false" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Mapel -->
        <div x-show="editMapelOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50" style="display: none;">
            <div @click.away="editMapelOpen = false" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 m-4">
                <div class="flex justify-between items-center mb-5 border-b pb-3">
                    <h3 class="text-lg font-bold text-gray-800">Edit Mata Pelajaran</h3>
                    <button @click="editMapelOpen = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form :action="`{{ url('admin/mapel') }}/${currentMapelId}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" x-model="currentMapelNama" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" @click="editMapelOpen = false" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
