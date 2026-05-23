<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\JurnalGuru;
use App\Models\TahunAjaran;
use App\Models\MasterJamPelajaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
{
    public function index(Request $request)
    {
        $activeTahunAjaran = TahunAjaran::where('status_aktif', 'Aktif')->first();
        if (!$activeTahunAjaran) {
            return redirect()->route('dashboard')->with('error', 'Tahun Ajaran aktif belum diset.');
        }

        $tanggal = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
        
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
        $guruId = Auth::user()->guru->id ?? null;

        if (!$guruId) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum terhubung dengan data Guru.');
        }

        // Ambil jadwal asli guru ini pada hari tersebut
        // Pastikan kita meload data guru penggantinya jika ada
        $jadwalsAsli = Jadwal::with(['kelas', 'mataPelajaran', 'guruPenggantis' => function($q) use ($tanggal) {
                $q->with('guruPengganti')->where('tanggal_mengajar', $tanggal);
            }])
            ->where('tahun_ajaran_id', $activeTahunAjaran->id)
            ->where('guru_id', $guruId)
            ->where('hari', $namaHari)
            ->get()
            ->map(function($jadwal) {
                if ($jadwal->guruPenggantis->isNotEmpty()) {
                    $jadwal->is_digantikan = true;
                    $jadwal->nama_guru_pengganti = $jadwal->guruPenggantis->first()->guruPengganti->nama_lengkap ?? 'Guru Pengganti';
                } else {
                    $jadwal->is_digantikan = false;
                }
                return $jadwal;
            });

        // Ambil jadwal di mana guru ini bertindak sebagai pengganti pada tanggal tersebut
        $jadwalsPengganti = Jadwal::with(['kelas', 'mataPelajaran'])
            ->whereHas('guruPenggantis', function($q) use ($tanggal, $guruId) {
                $q->where('tanggal_mengajar', $tanggal)
                  ->where('guru_pengganti_id', $guruId);
            })
            ->where('tahun_ajaran_id', $activeTahunAjaran->id)
            // hari sudah pasti sesuai karena di guru_penggantis kita sudah set berdasarkan tanggal
            ->get()
            ->map(function($jadwal) {
                $jadwal->is_pengganti = true;
                return $jadwal;
            });

        // Gabungkan jadwal asli dan pengganti
        $jadwals = $jadwalsAsli->concat($jadwalsPengganti)->sortBy('jam_ke_mulai');

        // Ambil data jurnal yang sudah diisi pada tanggal ini
        $jurnalsFilled = JurnalGuru::where('tanggal_mengajar', $tanggal)
            ->whereIn('jadwal_id', $jadwals->pluck('id'))
            ->get()
            ->keyBy('jadwal_id');

        $jamPelajarans = MasterJamPelajaran::orderBy('jam_ke')->get()->keyBy('jam_ke');

        return view('guru.jurnal.index', compact('jadwals', 'tanggal', 'namaHari', 'jurnalsFilled', 'jamPelajarans'));
    }

    public function create(Request $request, Jadwal $jadwal)
    {
        $tanggal = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
        
        // Cek apakah jurnal sudah diisi
        $jurnal = JurnalGuru::where('jadwal_id', $jadwal->id)
            ->where('tanggal_mengajar', $tanggal)
            ->first();

        if ($jurnal) {
            return redirect()->route('guru.jurnal.index', ['tanggal' => $tanggal])
                             ->with('error', 'Jurnal untuk jadwal ini sudah diisi.');
        }

        $jamPelajarans = MasterJamPelajaran::orderBy('jam_ke')->get()->keyBy('jam_ke');
        
        return view('guru.jurnal.create', compact('jadwal', 'tanggal', 'jamPelajarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'tanggal_mengajar' => 'required|date',
            'materi_pembelajaran' => 'required|string',
            'catatan_tambahan' => 'nullable|string',
        ]);

        $guruId = Auth::user()->guru->id;

        // Cek duplicate
        $exists = JurnalGuru::where('jadwal_id', $request->jadwal_id)
            ->where('tanggal_mengajar', $request->tanggal_mengajar)
            ->exists();

        if ($exists) {
            return redirect()->route('guru.jurnal.index', ['tanggal' => $request->tanggal_mengajar])
                             ->with('error', 'Jurnal sudah diisi sebelumnya.');
        }

        JurnalGuru::create([
            'jadwal_id' => $request->jadwal_id,
            'tanggal_mengajar' => $request->tanggal_mengajar,
            'guru_pengisi_id' => $guruId,
            'materi_pembelajaran' => $request->materi_pembelajaran,
            'catatan_tambahan' => $request->catatan_tambahan,
            'status_validasi' => 'Pending'
        ]);

        return redirect()->route('guru.jurnal.index', ['tanggal' => $request->tanggal_mengajar])
                         ->with('success', 'Jurnal berhasil disimpan.');
    }

    public function riwayat(Request $request)
    {
        $guruId = Auth::user()->guru->id ?? null;
        if (!$guruId) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum terhubung dengan data Guru.');
        }

        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->format('Y'));
        
        $activeTahunAjaran = TahunAjaran::where('status_aktif', 'Aktif')->first();

        $jurnals = JurnalGuru::with(['jadwal.kelas', 'jadwal.mataPelajaran'])
            ->where('guru_pengisi_id', $guruId)
            ->whereMonth('tanggal_mengajar', $bulan)
            ->whereYear('tanggal_mengajar', $tahun)
            ->whereHas('jadwal', function($q) use ($activeTahunAjaran) {
                if ($activeTahunAjaran) {
                    $q->where('tahun_ajaran_id', $activeTahunAjaran->id);
                }
            })
            ->orderBy('tanggal_mengajar', 'desc')
            ->orderBy(function($query) {
                $query->select('jam_ke_mulai')
                      ->from('jadwals')
                      ->whereColumn('jadwals.id', 'jurnal_gurus.jadwal_id');
            })
            ->get();

        return view('guru.jurnal.riwayat', compact('jurnals', 'bulan', 'tahun'));
    }
}
