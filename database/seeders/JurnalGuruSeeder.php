<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JurnalGuru;
use App\Models\Jadwal;
use Carbon\Carbon;

class JurnalGuruSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::now()->format('Y-m-d');
        
        $hariMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $namaHariIni = $hariMap[date('l', strtotime($today))];

        // Ambil beberapa jadwal hari ini
        $jadwalsHariIni = Jadwal::where('hari', $namaHariIni)->take(5)->get();

        foreach ($jadwalsHariIni as $index => $jadwal) {
            $status = 'Pending';
            $catatanKepsek = null;

            if ($index % 3 == 0) {
                $status = 'Disetujui';
                $catatanKepsek = 'Terima kasih atas laporan yang lengkap.';
            } elseif ($index % 3 == 1) {
                $status = 'Revisi';
                $catatanKepsek = 'Mohon lengkapi detail materi yang diajarkan.';
            }

            JurnalGuru::create([
                'jadwal_id' => $jadwal->id,
                'guru_pengisi_id' => $jadwal->guru_id,
                'tanggal_mengajar' => $today,
                'materi_pembelajaran' => 'Membahas bab ' . ($index + 1) . ' sesuai dengan silabus semester ganjil. Siswa melakukan diskusi kelompok.',
                'catatan_tambahan' => 'Ada 2 siswa yang izin sakit.',
                'status_validasi' => $status,
                'catatan_kepsek' => $catatanKepsek,
            ]);
        }
    }
}
