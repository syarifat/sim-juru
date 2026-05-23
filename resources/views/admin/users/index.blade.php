<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Kelola Pengguna & Guru</h2>
                <p class="text-sm text-gray-500">Daftar hak akses login, akun administrator, serta profil guru.</p>
            </div>
            <div>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pengguna
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan nama, username, atau NIP..." class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                    Cari
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4">NIP</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-6 py-4 font-medium">{{ $users->firstItem() + $index }}</td>
                                <td class="px-6 py-4 font-semibold text-emerald-600">{{ $user->username }}</td>
                                <td class="px-6 py-4">{{ $user->guru->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4"><span class="font-mono text-gray-600">{{ $user->guru->nip ?? '-' }}</span></td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                                        {{ $user->role === 'Admin' ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}
                                        {{ $user->role === 'Kepala_Sekolah' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                        {{ $user->role === 'Guru' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}">
                                        {{ str_replace('_', ' ', $user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end space-x-3">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-amber-600 hover:text-amber-700 font-medium text-sm">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400">Data pengguna tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="block md:hidden divide-y divide-gray-100">
                @forelse($users as $index => $user)
                    <div class="p-3 bg-white hover:bg-emerald-50/20 transition-colors flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono text-gray-400">#{{ $users->firstItem() + $index }}</span>
                                <span class="text-sm font-bold text-emerald-600">{{ $user->username }}</span>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full 
                                {{ $user->role === 'Admin' ? 'bg-purple-50 text-purple-700 border border-purple-100' : '' }}
                                {{ $user->role === 'Kepala_Sekolah' ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                                {{ $user->role === 'Guru' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : '' }}">
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                        </div>
                        @if($user->role !== 'Admin')
                            <div class="text-xs text-gray-650 font-medium">
                                <span class="font-bold text-gray-800">{{ $user->guru->nama_lengkap ?? '-' }}</span>
                                <span class="text-gray-400 ml-1">| NIP: <span class="font-mono">{{ $user->guru->nip ?? '-' }}</span></span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between pt-1.5 border-t border-gray-55 text-xs">
                            <span class="text-gray-400 font-medium">Aksi</span>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-amber-600 hover:text-amber-700 font-semibold">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-semibold">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-400 text-sm">Data pengguna tidak ditemukan.</div>
                @endforelse
            </div>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $users->appends(['search' => $search])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
