<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JurnalGuru;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $activeTahunAjaran = TahunAjaran::where('status_aktif', 'Aktif')->first();
        
        $tahunAjaranId = $request->input('tahun_ajaran_id', $activeTahunAjaran?->id);
        $guruId = $request->input('guru_id');
        $statusValidasi = $request->input('status_validasi');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $query = JurnalGuru::with(['jadwal.kelas', 'jadwal.mataPelajaran', 'guruPengisi'])
            ->whereBetween('tanggal_mengajar', [$startDate, $endDate]);

        if ($tahunAjaranId) {
            $query->whereHas('jadwal', function($q) use ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId);
            });
        }

        if ($guruId) {
            $query->where('guru_pengisi_id', $guruId);
        }

        if ($statusValidasi) {
            $query->where('status_validasi', $statusValidasi);
        }

        $jurnals = $query->orderBy('tanggal_mengajar', 'desc')
            ->orderBy(function($query) {
                $query->select('jam_ke_mulai')
                      ->from('jadwals')
                      ->whereColumn('jadwals.id', 'jurnal_gurus.jadwal_id');
            })
            ->get();

        $tahunAjarans = TahunAjaran::orderBy('tahun', 'desc')->get();
        $gurus = Guru::orderBy('nama_lengkap')->get();

        if ($request->input('action') === 'export_pdf') {
            $pdf = Pdf::loadView('admin.laporan.pdf', compact('jurnals', 'startDate', 'endDate', 'gurus', 'guruId', 'statusValidasi'));
            return $pdf->download('laporan_jurnal_' . date('Ymd_His') . '.pdf');
        }

        return view('admin.laporan.index', compact('jurnals', 'tahunAjarans', 'gurus', 'tahunAjaranId', 'guruId', 'statusValidasi', 'startDate', 'endDate'));
    }
}
