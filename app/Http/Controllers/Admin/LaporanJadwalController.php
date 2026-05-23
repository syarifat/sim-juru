<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\TahunAjaran;
use App\Models\MasterJamPelajaran;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanJadwalController extends Controller
{
    public function index(Request $request)
    {
        $activeTahunAjaran = TahunAjaran::where('status_aktif', 'Aktif')->first();
        if (!$activeTahunAjaran) {
            return redirect()->route('dashboard')->with('error', 'Tahun Ajaran aktif belum ditentukan.');
        }

        $tahunAjaranId = $request->input('tahun_ajaran_id', $activeTahunAjaran?->id);
        $guruId = $request->input('guru_id');

        $query = Jadwal::with(['kelas', 'mataPelajaran', 'guru', 'tahunAjaran'])
            ->where('tahun_ajaran_id', $tahunAjaranId);

        if ($guruId) {
            $query->where('guru_id', $guruId);
        }

        $jadwals = $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_ke_mulai')
            ->get();

        $tahunAjarans = TahunAjaran::orderBy('tahun', 'desc')->get();
        $gurus = Guru::orderBy('nama_lengkap')->get();
        $selectedGuru = $guruId ? Guru::find($guruId) : null;
        $selectedTahunAjaran = TahunAjaran::find($tahunAjaranId);

        $jamPelajarans = MasterJamPelajaran::orderBy('jam_ke')
            ->get()
            ->keyBy('jam_ke');

        if ($request->input('action') === 'export_pdf') {
            $pdf = Pdf::loadView('admin.laporan.jadwal_pdf', compact('jadwals', 'selectedGuru', 'selectedTahunAjaran', 'jamPelajarans'));
            $filename = $selectedGuru 
                ? 'jadwal_mengajar_' . str_replace(' ', '_', strtolower($selectedGuru->nama_lengkap)) . '_' . date('Ymd') . '.pdf'
                : 'jadwal_mengajar_semua_guru_' . date('Ymd') . '.pdf';
            return $pdf->download($filename);
        }

        return view('admin.laporan.jadwal', compact('jadwals', 'tahunAjarans', 'gurus', 'tahunAjaranId', 'guruId', 'selectedGuru', 'selectedTahunAjaran', 'jamPelajarans'));
    }
}
