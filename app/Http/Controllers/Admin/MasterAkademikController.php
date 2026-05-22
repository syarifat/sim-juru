<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MasterAkademikController extends Controller
{
    public function index()
    {
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();

        return view('admin.master_akademik.index', compact('kelases', 'mapels'));
    }

    public function storeKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas'
        ], [
            'nama_kelas.unique' => 'Nama kelas sudah ada.'
        ]);

        Kelas::create($request->all());

        return redirect()->route('admin.kelas-mapel.index')->with('success_kelas', 'Kelas berhasil ditambahkan.');
    }

    public function updateKelas(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $kelas->id
        ], [
            'nama_kelas.unique' => 'Nama kelas sudah ada.'
        ]);

        $kelas->update($request->all());

        return redirect()->route('admin.kelas-mapel.index')->with('success_kelas', 'Kelas berhasil diperbarui.');
    }

    public function destroyKelas(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('admin.kelas-mapel.index')->with('success_kelas', 'Kelas berhasil dihapus.');
    }

    public function storeMapel(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255|unique:mata_pelajarans,nama_mapel'
        ], [
            'nama_mapel.unique' => 'Nama mata pelajaran sudah ada.'
        ]);

        MataPelajaran::create($request->all());

        return redirect()->route('admin.kelas-mapel.index')->with('success_mapel', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function updateMapel(Request $request, MataPelajaran $mapel)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255|unique:mata_pelajarans,nama_mapel,' . $mapel->id
        ], [
            'nama_mapel.unique' => 'Nama mata pelajaran sudah ada.'
        ]);

        $mapel->update($request->all());

        return redirect()->route('admin.kelas-mapel.index')->with('success_mapel', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroyMapel(MataPelajaran $mapel)
    {
        $mapel->delete();
        return redirect()->route('admin.kelas-mapel.index')->with('success_mapel', 'Mata Pelajaran berhasil dihapus.');
    }
}
