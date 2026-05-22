<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'Admin',
        ]);

        // 2. Kepala Sekolah
        User::create([
            'username' => 'kepsek',
            'password' => Hash::make('password'),
            'role' => 'Kepala_Sekolah',
        ]);

        // 3. Guru (5 orang)
        $gurus = [
            ['username' => 'guru1', 'nama' => 'Budi Santoso, S.Pd', 'nip' => '198001012005011001'],
            ['username' => 'guru2', 'nama' => 'Siti Aminah, M.Pd', 'nip' => '198202022006022002'],
            ['username' => 'guru3', 'nama' => 'Ahmad Fauzi, S.Kom', 'nip' => '198503032008031003'],
            ['username' => 'guru4', 'nama' => 'Rina Wijayanti, S.Si', 'nip' => '198804042010042004'],
            ['username' => 'guru5', 'nama' => 'Eko Prasetyo, S.Pd', 'nip' => '199005052014051005'],
        ];

        foreach ($gurus as $g) {
            $user = User::create([
                'username' => $g['username'],
                'password' => Hash::make('password'),
                'role' => 'Guru',
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nip' => $g['nip'],
                'nama_lengkap' => $g['nama'],
            ]);
        }
    }
}
