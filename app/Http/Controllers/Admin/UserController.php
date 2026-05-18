<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Fitur pencarian sederhana berdasarkan username atau nama lengkap
        $search = $request->input('search');

        $users = User::with('guru')
            ->when($search, function ($query, $search) {
                $query->where('username', 'like', "%{$search}%")
                      ->orWhereHas('guru', function ($q) use ($search) {
                          $q->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nip', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:Admin,Kepala_Sekolah,Guru',
            'nip' => 'nullable|required_if:role,Guru,Kepala_Sekolah|string|max:50',
            'nama_lengkap' => 'required_if:role,Guru,Kepala_Sekolah|string|max:255',
        ], [
            'required_if' => 'Kolom :attribute wajib diisi jika role bukan Admin.',
            'unique' => 'Username sudah digunakan.'
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Jika role-nya Guru atau Kepsek, simpan juga ke tabel guru
            if (in_array($request->role, ['Guru', 'Kepala_Sekolah'])) {
                Guru::create([
                    'user_id' => $user->id,
                    'nip' => $request->nip,
                    'nama_lengkap' => $request->nama_lengkap,
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        // Eager load data profil guru
        $user->load('guru');
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:Admin,Kepala_Sekolah,Guru',
            'nip' => 'nullable|required_if:role,Guru,Kepala_Sekolah|string|max:50',
            'nama_lengkap' => 'required_if:role,Guru,Kepala_Sekolah|string|max:255',
        ], [
            'required_if' => 'Kolom :attribute wajib diisi jika role bukan Admin.'
        ]);

        DB::transaction(function () use ($request, $user) {
            // Update data User
            $user->username = $request->username;
            $user->role = $request->role;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            if (in_array($request->role, ['Guru', 'Kepala_Sekolah'])) {
                // Update atau buat baru data guru jika sebelumnya admin berganti role
                Guru::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => $request->nip,
                        'nama_lengkap' => $request->nama_lengkap
                    ]
                );
            } else {
                // Jika role diubah menjadi Admin, hapus record guru lamanya jika ada
                $user->guru()->delete();
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Otomatis menghapus record di tabel guru karena cascadeOnDelete di migration
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}