<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mengecek agar tidak terjadi duplikat jika seeder dijalankan berulang kali
        if (!User::where('username', 'admin')->exists()) {
            User::create([
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role'     => 'Admin',
            ]);
            
            $this->command->info('User Admin berhasil dibuat! (Username: admin | Password: password)');
        } else {
            $this->command->info('User Admin sudah ada di database.');
        }
    }
}