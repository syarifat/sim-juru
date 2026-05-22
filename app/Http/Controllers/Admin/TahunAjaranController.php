<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunAjaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $tahunAjarans = TahunAjaran::when($search, function ($query, $search) {
                $query->where('tahun', 'like', "%{$search}%")
                      ->orWhere('semester', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.tahun_ajaran.index', compact('tahunAjarans', 'search'));
    }

    public function create()
    {
        return view('admin.tahun_ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string|max:50',
            'semester' => 'required|in:Ganjil,Genap',
            'status_aktif' => 'required|in:Aktif,Tidak Aktif',
        ]);

        DB::transaction(function () use ($request) {
            if ($request->status_aktif === 'Aktif') {
                TahunAjaran::where('status_aktif', 'Aktif')->update(['status_aktif' => 'Tidak Aktif']);
            }
            TahunAjaran::create($request->all());
        });

        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('admin.tahun_ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'tahun' => 'required|string|max:50',
            'semester' => 'required|in:Ganjil,Genap',
            'status_aktif' => 'required|in:Aktif,Tidak Aktif',
        ]);

        DB::transaction(function () use ($request, $tahunAjaran) {
            if ($request->status_aktif === 'Aktif') {
                TahunAjaran::where('id', '!=', $tahunAjaran->id)
                    ->where('status_aktif', 'Aktif')
                    ->update(['status_aktif' => 'Tidak Aktif']);
            }
            $tahunAjaran->update($request->all());
        });

        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->status_aktif === 'Aktif') {
            return redirect()->route('admin.tahun-ajaran.index')->with('error', 'Tahun Ajaran aktif tidak dapat dihapus.');
        }
        
        $tahunAjaran->delete();
        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
}
