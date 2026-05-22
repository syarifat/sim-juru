<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $mapels = [
            'Pendidikan Agama Islam',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Bahasa Indonesia',
            'Matematika',
            'Sejarah Indonesia',
            'Bahasa Inggris',
            'Seni Budaya',
            'Pendidikan Jasmani, Olahraga, dan Kesehatan',
            'Fisika',
            'Kimia',
            'Biologi',
        ];

        foreach ($mapels as $mapel) {
            MataPelajaran::create(['nama_mapel' => $mapel]);
        }
    }
}
