<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = [
        'tahun_ajaran_id', 
        'guru_id', 
        'mata_pelajaran_id', 
        'kelas_id', 
        'hari', 
        'jam_ke_mulai', 
        'jam_ke_selesai'
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jurnals()
    {
        return $this->hasMany(JurnalGuru::class);
    }
}