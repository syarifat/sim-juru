<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Jadwal;
use App\Models\TahunAjaran;
use App\Models\JurnalGuru;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;
        $activeTahunAjaran = TahunAjaran::where('status_aktif', 'Aktif')->first();
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
        $namaHari = $hariMap[date('l', strtotime($today))];

        $data = [
            'role' => $role,
            'activeTahunAjaran' => $activeTahunAjaran,
            'today' => $today,
            'namaHari' => $namaHari,
        ];

        if ($role === 'Admin') {
            $data['totalGuru'] = Guru::count();
            $data['totalKelas'] = Kelas::count();
            $data['totalMapel'] = MataPelajaran::count();
            $data['totalJadwal'] = $activeTahunAjaran ? Jadwal::where('tahun_ajaran_id', $activeTahunAjaran->id)->count() : 0;
            
        } elseif ($role === 'Guru') {
            $guruId = $user->guru->id ?? null;
            if ($guruId && $activeTahunAjaran) {
                // Cari jadwal asli
                $jadwalsAsli = Jadwal::where('tahun_ajaran_id', $activeTahunAjaran->id)
                    ->where('guru_id', $guruId)
                    ->where('hari', $namaHari)
                    ->whereDoesntHave('guruPenggantis', function($q) use ($today) {
                        $q->where('tanggal_mengajar', $today);
                    })
                    ->get();
                
                // Cari jadwal pengganti
                $jadwalsPengganti = Jadwal::where('tahun_ajaran_id', $activeTahunAjaran->id)
                    ->whereHas('guruPenggantis', function($q) use ($today, $guruId) {
                        $q->where('tanggal_mengajar', $today)
                          ->where('guru_pengganti_id', $guruId);
                    })->get();

                $semuaJadwalHariIni = $jadwalsAsli->concat($jadwalsPengganti);
                
                $data['totalJadwalHariIni'] = $semuaJadwalHariIni->count();
                $data['totalJurnalTerisi'] = JurnalGuru::where('tanggal_mengajar', $today)
                    ->whereIn('jadwal_id', $semuaJadwalHariIni->pluck('id'))
                    ->count();
            } else {
                $data['totalJadwalHariIni'] = 0;
                $data['totalJurnalTerisi'] = 0;
            }
            
        } elseif ($role === 'Kepala_Sekolah') {
            if ($activeTahunAjaran) {
                $jurnalsHariIni = JurnalGuru::where('tanggal_mengajar', $today)
                    ->whereHas('jadwal', function($q) use ($activeTahunAjaran) {
                        $q->where('tahun_ajaran_id', $activeTahunAjaran->id);
                    })
                    ->get();
                    
                $data['totalJurnalPending'] = $jurnalsHariIni->where('status_validasi', 'Pending')->count();
                $data['totalJurnalDisetujui'] = $jurnalsHariIni->where('status_validasi', 'Disetujui')->count();
                $data['totalJurnalRevisi'] = $jurnalsHariIni->where('status_validasi', 'Revisi')->count();
                
                // Jurnal yang belum divalidasi (Pending) untuk daftar singkat
                $data['jurnalPendings'] = JurnalGuru::with(['guruPengisi', 'jadwal.kelas', 'jadwal.mataPelajaran'])
                    ->where('tanggal_mengajar', $today)
                    ->where('status_validasi', 'Pending')
                    ->whereHas('jadwal', function($q) use ($activeTahunAjaran) {
                        $q->where('tahun_ajaran_id', $activeTahunAjaran->id);
                    })
                    ->limit(5)
                    ->get();
            } else {
                $data['totalJurnalPending'] = 0;
                $data['totalJurnalDisetujui'] = 0;
                $data['totalJurnalRevisi'] = 0;
                $data['jurnalPendings'] = collect();
            }
        }

        return view('dashboard', $data);
    }
}
