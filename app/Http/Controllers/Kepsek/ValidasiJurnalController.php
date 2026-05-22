<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JurnalGuru;
use App\Models\Guru;
use Carbon\Carbon;

class ValidasiJurnalController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
        $guruId = $request->input('guru_id');

        $gurus = Guru::orderBy('nama_lengkap')->get();
        $activeTahunAjaran = \App\Models\TahunAjaran::where('status_aktif', 'Aktif')->first();

        $query = JurnalGuru::with(['jadwal.kelas', 'jadwal.mataPelajaran', 'guruPengisi'])
            ->where('tanggal_mengajar', $tanggal)
            ->whereHas('jadwal', function($q) use ($activeTahunAjaran) {
                if ($activeTahunAjaran) {
                    $q->where('tahun_ajaran_id', $activeTahunAjaran->id);
                }
            });

        if ($guruId) {
            $query->where('guru_pengisi_id', $guruId);
        }

        $jurnals = $query->orderBy('status_validasi')
            ->orderBy(function($query) {
                $query->select('jam_ke_mulai')
                      ->from('jadwals')
                      ->whereColumn('jadwals.id', 'jurnal_gurus.jadwal_id');
            })
            ->get();

        return view('kepsek.validasi.index', compact('jurnals', 'tanggal', 'guruId', 'gurus'));
    }

    public function edit(JurnalGuru $jurnal)
    {
        $jurnal->load(['jadwal.kelas', 'jadwal.mataPelajaran', 'guruPengisi']);
        return view('kepsek.validasi.edit', compact('jurnal'));
    }

    public function update(Request $request, JurnalGuru $jurnal)
    {
        $request->validate([
            'status_validasi' => 'required|in:Disetujui,Revisi',
            'catatan_kepsek' => 'nullable|string'
        ]);

        $jurnal->update([
            'status_validasi' => $request->status_validasi,
            'catatan_kepsek' => $request->catatan_kepsek
        ]);

        return redirect()->route('kepsek.validasi.index', ['tanggal' => $jurnal->tanggal_mengajar])
                         ->with('success', 'Jurnal berhasil divalidasi.');
    }
}
