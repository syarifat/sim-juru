<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\TahunAjaran;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\MasterJamPelajaran;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $hari = $request->input('hari');

        $activeTahunAjaran = TahunAjaran::where('status_aktif', 'Aktif')->first();
        $tahunAjaranId = $request->input('tahun_ajaran_id', $activeTahunAjaran?->id);

        $jadwals = Jadwal::with(['guru', 'mataPelajaran', 'kelas', 'tahunAjaran'])
            ->when($tahunAjaranId, function($query) use ($tahunAjaranId) {
                return $query->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->when($kelasId, function($query) use ($kelasId) {
                return $query->where('kelas_id', $kelasId);
            })
            ->when($hari, function($query) use ($hari) {
                return $query->where('hari', $hari);
            })
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_ke_mulai')
            ->paginate(15);

        $kelases = Kelas::orderBy('nama_kelas')->get();
        $tahunAjarans = TahunAjaran::orderBy('tahun', 'desc')->get();
        $jamPelajarans = MasterJamPelajaran::orderBy('jam_ke')->get()->keyBy('jam_ke');

        return view('admin.jadwal.index', compact('jadwals', 'kelases', 'tahunAjarans', 'kelasId', 'hari', 'tahunAjaranId', 'jamPelajarans'));
    }

    public function create()
    {
        $activeTahunAjaran = TahunAjaran::where('status_aktif', 'Aktif')->first();
        if (!$activeTahunAjaran) {
            return redirect()->route('admin.jadwal.index')->with('error', 'Silakan set Tahun Ajaran aktif terlebih dahulu.');
        }

        $gurus = Guru::orderBy('nama_lengkap')->get();
        $mataPelajarans = MataPelajaran::orderBy('nama_mapel')->get();
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $maxJam = MasterJamPelajaran::max('jam_ke') ?? 10;
        $jamPelajarans = MasterJamPelajaran::orderBy('jam_ke')->get()->keyBy('jam_ke');

        return view('admin.jadwal.create', compact('activeTahunAjaran', 'gurus', 'mataPelajarans', 'kelases', 'maxJam', 'jamPelajarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jadwals' => 'required|array|min:1',
            'jadwals.*.guru_id' => 'required|exists:gurus,id',
            'jadwals.*.mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'jadwals.*.jam_ke_mulai' => 'required|integer|min:1',
            'jadwals.*.jam_ke_selesai' => 'required|integer|gte:jadwals.*.jam_ke_mulai',
        ]);

        $errors = [];

        // 1. Cek bentrok antar baris input dalam form itu sendiri
        foreach ($request->jadwals as $i => $ji) {
            foreach ($request->jadwals as $j => $jj) {
                if ($i === $j) continue;
                if ($ji['jam_ke_mulai'] <= $jj['jam_ke_selesai'] && $ji['jam_ke_selesai'] >= $jj['jam_ke_mulai']) {
                    $errors["jadwals.{$i}.jam_ke_mulai"] = "Jam pelajaran ini bertabrakan dengan baris input lain di form.";
                }
            }
        }

        // 2. Cek bentrok dengan data yang ada di database (Interval Overlap Check: A <= Y AND B >= X)
        foreach ($request->jadwals as $index => $j) {
            $bentrokGuru = Jadwal::where('tahun_ajaran_id', $request->tahun_ajaran_id)
                ->where('guru_id', $j['guru_id'])
                ->where('hari', $request->hari)
                ->where('jam_ke_mulai', '<=', $j['jam_ke_selesai'])
                ->where('jam_ke_selesai', '>=', $j['jam_ke_mulai'])
                ->exists();

            if ($bentrokGuru) {
                $errors["jadwals.{$index}.guru_id"] = "Guru ini sudah memiliki jadwal pada jam tersebut.";
            }

            $bentrokKelas = Jadwal::where('tahun_ajaran_id', $request->tahun_ajaran_id)
                ->where('kelas_id', $request->kelas_id)
                ->where('hari', $request->hari)
                ->where('jam_ke_mulai', '<=', $j['jam_ke_selesai'])
                ->where('jam_ke_selesai', '>=', $j['jam_ke_mulai'])
                ->exists();

            if ($bentrokKelas) {
                $errors["jadwals.{$index}.jam_ke_mulai"] = "Kelas ini sudah terisi pada jam tersebut.";
            }
        }

        if (count($errors) > 0) {
            return back()->withInput()->withErrors($errors);
        }

        foreach ($request->jadwals as $j) {
            Jadwal::create([
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'kelas_id' => $request->kelas_id,
                'hari' => $request->hari,
                'guru_id' => $j['guru_id'],
                'mata_pelajaran_id' => $j['mata_pelajaran_id'],
                'jam_ke_mulai' => $j['jam_ke_mulai'],
                'jam_ke_selesai' => $j['jam_ke_selesai'],
            ]);
        }

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $tahunAjarans = TahunAjaran::orderBy('tahun', 'desc')->get();
        $gurus = Guru::orderBy('nama_lengkap')->get();
        $mataPelajarans = MataPelajaran::orderBy('nama_mapel')->get();
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $maxJam = MasterJamPelajaran::max('jam_ke') ?? 10;
        $jamPelajarans = MasterJamPelajaran::orderBy('jam_ke')->get()->keyBy('jam_ke');

        $batchJadwals = Jadwal::where('tahun_ajaran_id', $jadwal->tahun_ajaran_id)
            ->where('kelas_id', $jadwal->kelas_id)
            ->where('hari', $jadwal->hari)
            ->orderBy('jam_ke_mulai')
            ->get();

        return view('admin.jadwal.edit', compact('jadwal', 'batchJadwals', 'tahunAjarans', 'gurus', 'mataPelajarans', 'kelases', 'maxJam', 'jamPelajarans'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'jadwals' => 'required|array|min:1',
            'jadwals.*.id' => 'nullable|exists:jadwals,id',
            'jadwals.*.guru_id' => 'required|exists:gurus,id',
            'jadwals.*.mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'jadwals.*.jam_ke_mulai' => 'required|integer|min:1',
            'jadwals.*.jam_ke_selesai' => 'required|integer|gte:jadwals.*.jam_ke_mulai',
        ]);

        $errors = [];
        $submittedIds = [];

        // 1. Cek bentrok antar baris input dalam form itu sendiri
        foreach ($request->jadwals as $i => $ji) {
            foreach ($request->jadwals as $j => $jj) {
                if ($i === $j) continue;
                if ($ji['jam_ke_mulai'] <= $jj['jam_ke_selesai'] && $ji['jam_ke_selesai'] >= $jj['jam_ke_mulai']) {
                    $errors["jadwals.{$i}.jam_ke_mulai"] = "Jam pelajaran ini bertabrakan dengan baris input lain di form.";
                }
            }
        }

        // 2. Cek bentrok dengan data yang ada di database (Interval Overlap Check: A <= Y AND B >= X)
        foreach ($request->jadwals as $index => $j) {
            $currentId = $j['id'] ?? null;
            if ($currentId) {
                $submittedIds[] = $currentId;
            }

            $bentrokGuru = Jadwal::where('tahun_ajaran_id', $jadwal->tahun_ajaran_id)
                ->where('guru_id', $j['guru_id'])
                ->where('hari', $jadwal->hari)
                ->when($currentId, function($q) use ($currentId) {
                    return $q->where('id', '!=', $currentId);
                })
                ->where('jam_ke_mulai', '<=', $j['jam_ke_selesai'])
                ->where('jam_ke_selesai', '>=', $j['jam_ke_mulai'])
                ->exists();

            if ($bentrokGuru) {
                $errors["jadwals.{$index}.guru_id"] = "Guru ini sudah memiliki jadwal pada jam tersebut.";
            }

            $bentrokKelas = Jadwal::where('tahun_ajaran_id', $jadwal->tahun_ajaran_id)
                ->where('kelas_id', $jadwal->kelas_id)
                ->where('hari', $jadwal->hari)
                ->when($currentId, function($q) use ($currentId) {
                    return $q->where('id', '!=', $currentId);
                })
                ->where('jam_ke_mulai', '<=', $j['jam_ke_selesai'])
                ->where('jam_ke_selesai', '>=', $j['jam_ke_mulai'])
                ->exists();

            if ($bentrokKelas) {
                $errors["jadwals.{$index}.jam_ke_mulai"] = "Kelas ini sudah terisi pada jam tersebut.";
            }
        }

        if (count($errors) > 0) {
            return back()->withInput()->withErrors($errors);
        }

        $existingJadwals = Jadwal::where('tahun_ajaran_id', $jadwal->tahun_ajaran_id)
            ->where('kelas_id', $jadwal->kelas_id)
            ->where('hari', $jadwal->hari)
            ->pluck('id')->toArray();

        $idsToDelete = array_diff($existingJadwals, $submittedIds);
        
        if (!empty($idsToDelete)) {
            Jadwal::whereIn('id', $idsToDelete)->delete();
        }

        foreach ($request->jadwals as $j) {
            if (isset($j['id'])) {
                Jadwal::where('id', $j['id'])->update([
                    'guru_id' => $j['guru_id'],
                    'mata_pelajaran_id' => $j['mata_pelajaran_id'],
                    'jam_ke_mulai' => $j['jam_ke_mulai'],
                    'jam_ke_selesai' => $j['jam_ke_selesai'],
                ]);
            } else {
                Jadwal::create([
                    'tahun_ajaran_id' => $jadwal->tahun_ajaran_id,
                    'kelas_id' => $jadwal->kelas_id,
                    'hari' => $jadwal->hari,
                    'guru_id' => $j['guru_id'],
                    'mata_pelajaran_id' => $j['mata_pelajaran_id'],
                    'jam_ke_mulai' => $j['jam_ke_mulai'],
                    'jam_ke_selesai' => $j['jam_ke_selesai'],
                ]);
            }
        }

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
