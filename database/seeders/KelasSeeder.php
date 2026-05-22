<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelases = [
            'X-A',
            'X-B',
            'X-C',
        ];

        foreach ($kelases as $kelas) {
            Kelas::create(['nama_kelas' => $kelas]);
        }
    }
}
