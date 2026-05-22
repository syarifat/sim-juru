<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterJamPelajaran;
use Illuminate\Http\Request;

class MasterJamPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $hariFilter = $request->input('hari');
        
        $jamPelajarans = MasterJamPelajaran::when($hariFilter, function($query) use ($hariFilter) {
                return $query->where('hari', $hariFilter);
            })
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_ke')
            ->paginate(15);
            
        return view('admin.jam_pelajaran.index', compact('jamPelajarans', 'hariFilter'));
    }

    public function create()
    {
        return view('admin.jam_pelajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam' => 'required|array|min:1',
            'jam.*.jam_ke' => 'required|integer|min:0',
            'jam.*.jam_mulai' => 'required',
            'jam.*.jam_selesai' => 'required',
            'jam.*.keterangan' => 'nullable|string|max:255'
        ]);

        foreach ($request->jam as $j) {
            MasterJamPelajaran::create([
                'hari' => $request->hari,
                'jam_ke' => $j['jam_ke'],
                'jam_mulai' => $j['jam_mulai'],
                'jam_selesai' => $j['jam_selesai'],
                'keterangan' => $j['keterangan'] ?? null,
            ]);
        }

        return redirect()->route('admin.jam-pelajaran.index')->with('success', 'Jam pelajaran berhasil ditambahkan.');
    }

    public function edit(MasterJamPelajaran $jam_pelajaran)
    {
        return view('admin.jam_pelajaran.edit', compact('jam_pelajaran'));
    }

    public function update(Request $request, MasterJamPelajaran $jam_pelajaran)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_ke' => 'required|integer|min:0',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $jam_pelajaran->update($request->all());

        return redirect()->route('admin.jam-pelajaran.index')->with('success', 'Jam pelajaran berhasil diperbarui.');
    }

    public function destroy(MasterJamPelajaran $jam_pelajaran)
    {
        $jam_pelajaran->delete();
        return redirect()->route('admin.jam-pelajaran.index')->with('success', 'Jam pelajaran berhasil dihapus.');
    }
}
