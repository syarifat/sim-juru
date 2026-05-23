<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Master Jam Pelajaran</h2>
                <p class="text-sm text-gray-500">Kelola jadwal jam pelajaran dan waktu istirahat.</p>
            </div>
            <div>
                <a href="{{ route('admin.jam-pelajaran.create') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Jam
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('admin.jam-pelajaran.index') }}" class="flex gap-2 w-full sm:w-1/3">
                <div class="relative flex-1">
                    <select name="hari" class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500" onchange="this.form.submit()">
                        <option value="">Semua Hari</option>
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                            <option value="{{ $h }}" {{ $hariFilter == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Hari</th>
                            <th class="px-6 py-4">Jam Ke</th>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($jamPelajarans as $jam)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-6 py-4 font-medium">{{ $jam->hari }}</td>
                                <td class="px-6 py-4 font-semibold text-emerald-600">
                                    {{ $jam->jam_ke == 0 ? 'Istirahat' : $jam->jam_ke }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($jam->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jam->jam_selesai)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4">{{ $jam->keterangan ?? '-' }}</td>
                                <td class="px-6 py-4 text-right flex justify-end space-x-3">
                                    <a href="{{ route('admin.jam-pelajaran.edit', $jam->id) }}" class="text-amber-600 hover:text-amber-700 font-medium text-sm">Edit</a>
                                    <form action="{{ route('admin.jam-pelajaran.destroy', $jam->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400">Data jam pelajaran tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="block md:hidden divide-y divide-gray-100">
                @forelse($jamPelajarans as $jam)
                    <div class="p-4 space-y-3 bg-white hover:bg-gray-50/50">
                        <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                            <span class="text-xs font-semibold px-2 py-1 rounded bg-emerald-50 text-emerald-700">{{ $jam->hari }}</span>
                            <span class="text-xs font-bold">{{ $jam->jam_ke == 0 ? 'Istirahat' : 'Jam Ke-' . $jam->jam_ke }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <p class="text-xs text-gray-400 uppercase">Waktu</p>
                                <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($jam->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jam->jam_selesai)->format('H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase">Keterangan</p>
                                <p class="text-sm text-gray-800">{{ $jam->keterangan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-4 pt-2">
                            <a href="{{ route('admin.jam-pelajaran.edit', $jam->id) }}" class="text-sm font-semibold text-amber-600">Edit</a>
                            <form action="{{ route('admin.jam-pelajaran.destroy', $jam->id) }}" method="POST" onsubmit="return confirm('Yakin?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-semibold text-red-600">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 text-sm">Data jam pelajaran tidak ditemukan.</div>
                @endforelse
            </div>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $jamPelajarans->appends(['hari' => $hariFilter])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
