<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TahunAjaran;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        TahunAjaran::create([
            'tahun' => '2023/2024',
            'semester' => 'Genap',
            'status_aktif' => 'Tidak Aktif',
        ]);

        TahunAjaran::create([
            'tahun' => '2024/2025',
            'semester' => 'Ganjil',
            'status_aktif' => 'Aktif',
        ]);
    }
}
