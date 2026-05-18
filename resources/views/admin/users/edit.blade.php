<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="text-xl font-bold text-gray-800">Edit Data Pengguna</h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100" x-data="{ currentRole: '{{ old('role', $user->role) }}' }">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">Hak Akses (Role) <span class="text-red-500">*</span></label>
                    <select name="role" id="role" x-model="currentRole" class="w-full text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                        <option value="Guru">Guru</option>
                        <option value="Kepala_Sekolah">Kepala Sekolah</option>
                        <option value="Admin">Admin / Manajemen</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-1" />
                </div>

                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="w-full text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                    <x-input-error :messages="$errors->get('username')" class="mt-1" />
                </div>

                <div x-show="currentRole !== 'Admin'" x-transition:enter="transition ease-out duration-200" class="space-y-5 bg-gray-50/70 p-4 rounded-lg border border-gray-100">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap Guru <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $user->guru->nama_lengkap ?? '') }}" class="w-full text-sm border border-gray-300 bg-white rounded-lg focus:border-blue-500 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-1" />
                    </div>
                    <div>
                        <label for="nip" class="block text-sm font-semibold text-gray-700 mb-1">NIP / Kode Guru <span class="text-red-500">*</span></label>
                        <input type="text" name="nip" id="nip" value="{{ old('nip', $user->guru->nip ?? '') }}" class="w-full text-sm border border-gray-300 bg-white rounded-lg focus:border-blue-500 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('nip')" class="mt-1" />
                    </div>
                </div>

                <div class="p-4 bg-amber-50 text-amber-800 rounded-lg text-xs border border-amber-100">
                    <strong>Catatan Keamanan:</strong> Kosongkan kolom password di bawah ini jika tidak ingin mengubah password lama pengguna.
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" class="w-full text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="pt-4 flex justify-end space-x-3 border-t border-gray-100">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>