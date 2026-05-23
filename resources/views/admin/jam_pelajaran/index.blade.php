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

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Jam Ke</th>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($jamPelajarans as $jam)
                            <tr class="hover:bg-gray-50/70 transition-colors">
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
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400">Data jam pelajaran tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="block md:hidden divide-y divide-gray-100">
                @forelse($jamPelajarans as $jam)
                    <div class="p-3 bg-white hover:bg-emerald-50/20 transition-colors flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-emerald-600">{{ $jam->jam_ke == 0 ? 'Istirahat' : 'Jam Ke-' . $jam->jam_ke }}</span>
                            <span class="text-xs font-bold text-gray-700 bg-gray-50 px-2 py-0.5 rounded border border-gray-100">{{ \Carbon\Carbon::parse($jam->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jam->jam_selesai)->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Keterangan: <span class="font-semibold text-gray-700">{{ $jam->keterangan ?? '-' }}</span></span>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.jam-pelajaran.edit', $jam->id) }}" class="text-amber-600 hover:text-amber-700 font-semibold">Edit</a>
                                <form action="{{ route('admin.jam-pelajaran.destroy', $jam->id) }}" method="POST" onsubmit="return confirm('Yakin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-semibold">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-400 text-sm">Data jam pelajaran tidak ditemukan.</div>
                @endforelse
            </div>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $jamPelajarans->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
