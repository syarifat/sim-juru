<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterJamPelajaran;

class MasterJamPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $jams = [
            ['jam_ke' => 1, 'jam_mulai' => '07:00', 'jam_selesai' => '07:45'],
            ['jam_ke' => 2, 'jam_mulai' => '07:45', 'jam_selesai' => '08:30'],
            ['jam_ke' => 3, 'jam_mulai' => '08:30', 'jam_selesai' => '09:15'],
            ['jam_ke' => 4, 'jam_mulai' => '09:15', 'jam_selesai' => '10:00'],
            ['jam_ke' => 5, 'jam_mulai' => '10:30', 'jam_selesai' => '11:15'],
            ['jam_ke' => 6, 'jam_mulai' => '11:15', 'jam_selesai' => '12:00'],
            ['jam_ke' => 7, 'jam_mulai' => '12:30', 'jam_selesai' => '13:15'],
            ['jam_ke' => 8, 'jam_mulai' => '13:15', 'jam_selesai' => '14:00'],
            ['jam_ke' => 9, 'jam_mulai' => '14:00', 'jam_selesai' => '14:45'],
            ['jam_ke' => 10, 'jam_mulai' => '14:45', 'jam_selesai' => '15:30'],
        ];

        foreach ($jams as $jam) {
            MasterJamPelajaran::create($jam);
        }
    }
}
