<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuruPengganti;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunAjaran;
use App\Models\MasterJamPelajaran;
use Illuminate\Http\Request;

class GuruPenggantiController extends Controller
{
    public function index(Request $request)
    {
        $activeTahunAjaran = TahunAjaran::where('status_aktif', 'Aktif')->first();
        if (!$activeTahunAjaran) {
            return redirect()->route('dashboard')->with('error', 'Silakan set Tahun Ajaran aktif terlebih dahulu.');
        }

        $tanggal = $request->input('tanggal', date('Y-m-d'));
        
        $penggantis = GuruPengganti::with(['jadwal.kelas', 'jadwal.mataPelajaran', 'jadwal.guru', 'guruPengganti'])
            ->whereHas('jadwal', function($q) use ($activeTahunAjaran) {
                $q->where('tahun_ajaran_id', $activeTahunAjaran->id);
            })
            ->where('tanggal_mengajar', $tanggal)
            ->get();

        return view('admin.guru_pengganti.index', compact('penggantis', 'tanggal', 'activeTahunAjaran'));
    }

    public function create(Request $request)
    {
        $activeTahunAjaran = TahunAjaran::where('status_aktif', 'Aktif')->first();
        if (!$activeTahunAjaran) {
            return redirect()->route('dashboard')->with('error', 'Silakan set Tahun Ajaran aktif terlebih dahulu.');
        }

        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $kelasId = $request->input('kelas_id');
        
        // Convert date to hari (Senin, Selasa, etc.)
        $hariMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $namaHari = $hariMap[date('l', strtotime($tanggal))];

        $kelases = Kelas::orderBy('nama_kelas')->get();
        $gurus = Guru::orderBy('nama_lengkap')->get();
        
        $jadwals = collect();
        if ($kelasId) {
            $jadwals = Jadwal::with(['mataPelajaran', 'guru', 'guruPenggantis' => function($q) use ($tanggal) {
                    $q->where('tanggal_mengajar', $tanggal);
                }])
                ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                ->where('kelas_id', $kelasId)
                ->where('hari', $namaHari)
                ->orderBy('jam_ke_mulai')
                ->get();
        }

        $jamPelajarans = MasterJamPelajaran::orderBy('jam_ke')->get()->keyBy('jam_ke');

        return view('admin.guru_pengganti.create', compact('tanggal', 'kelasId', 'namaHari', 'kelases', 'jadwals', 'gurus', 'jamPelajarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kelas_id' => 'required|exists:kelas,id',
            'penggantis' => 'nullable|array',
            'penggantis.*.guru_pengganti_id' => 'nullable|exists:gurus,id',
        ]);

        $tanggal = $request->tanggal;
        $penggantis = $request->penggantis ?? [];

        // Delete existing for this class & date
        // Note: we only delete those that are part of this class
        $jadwalIds = Jadwal::where('kelas_id', $request->kelas_id)->pluck('id');
        GuruPengganti::whereIn('jadwal_id', $jadwalIds)->where('tanggal_mengajar', $tanggal)->delete();

        foreach ($penggantis as $jadwalId => $data) {
            if (!empty($data['guru_pengganti_id'])) {
                GuruPengganti::create([
                    'jadwal_id' => $jadwalId,
                    'tanggal_mengajar' => $tanggal,
                    'guru_pengganti_id' => $data['guru_pengganti_id'],
                ]);
            }
        }

        return redirect()->route('admin.guru-pengganti.index', ['tanggal' => $tanggal])
                         ->with('success', 'Data guru pengganti berhasil disimpan.');
    }

    public function destroy(GuruPengganti $guruPengganti)
    {
        $tanggal = $guruPengganti->tanggal_mengajar;
        $guruPengganti->delete();
        return redirect()->route('admin.guru-pengganti.index', ['tanggal' => $tanggal])
                         ->with('success', 'Data guru pengganti berhasil dibatalkan.');
    }
}
