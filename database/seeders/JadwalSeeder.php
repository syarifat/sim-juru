<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Guru;
use Carbon\Carbon;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaran = TahunAjaran::where('status_aktif', 'Aktif')->first();
        if (!$tahunAjaran) return;

        $kelases = Kelas::all();
        $mapels = MataPelajaran::all();
        $gurus = Guru::all();

        // Hari dalam bahasa Indonesia sesuai mapping di aplikasi
        $haris = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        foreach ($haris as $hari) {
            // Sesi 1: Jam 1-3
            $shuffledGurus1 = $gurus->shuffle()->values();
            foreach ($kelases as $index => $kelas) {
                $guru = $shuffledGurus1->get($index % $shuffledGurus1->count());
                
                Jadwal::create([
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'kelas_id' => $kelas->id,
                    'hari' => $hari,
                    'mata_pelajaran_id' => $mapels->random()->id,
                    'guru_id' => $guru->id,
                    'jam_ke_mulai' => 1,
                    'jam_ke_selesai' => 3,
                ]);
            }

            // Sesi 2: Jam 4-6
            $shuffledGurus2 = $gurus->shuffle()->values();
            foreach ($kelases as $index => $kelas) {
                $guru = $shuffledGurus2->get($index % $shuffledGurus2->count());

                Jadwal::create([
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'kelas_id' => $kelas->id,
                    'hari' => $hari,
                    'mata_pelajaran_id' => $mapels->random()->id,
                    'guru_id' => $guru->id,
                    'jam_ke_mulai' => 4,
                    'jam_ke_selesai' => 6,
                ]);
            }
        }
    }
}
