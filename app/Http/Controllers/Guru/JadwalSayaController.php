<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\TahunAjaran;
use App\Models\MasterJamPelajaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class JadwalSayaController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user()->guru;
        if (!$guru) {
            return redirect()->route('dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $activeTahunAjaran = TahunAjaran::where('status_aktif', 'Aktif')->first();
        if (!$activeTahunAjaran) {
            return redirect()->route('dashboard')->with('error', 'Tahun Ajaran aktif belum ditentukan.');
        }

        $jadwals = Jadwal::with(['kelas', 'mataPelajaran', 'tahunAjaran'])
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', $activeTahunAjaran->id)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_ke_mulai')
            ->get();

        $jamPelajarans = MasterJamPelajaran::orderBy('jam_ke')
            ->get()
            ->keyBy('jam_ke');

        if ($request->input('action') === 'export_pdf') {
            $pdf = Pdf::loadView('guru.jadwal.pdf', compact('jadwals', 'guru', 'activeTahunAjaran', 'jamPelajarans'));
            return $pdf->download('jadwal_mengajar_' . str_replace(' ', '_', strtolower($guru->nama_lengkap)) . '_' . date('Ymd') . '.pdf');
        }

        return view('guru.jadwal.saya', compact('jadwals', 'guru', 'activeTahunAjaran', 'jamPelajarans'));
    }
}
