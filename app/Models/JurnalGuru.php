<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalGuru extends Model
{
    protected $fillable = [
        'jadwal_id', 
        'tanggal_mengajar', 
        'guru_pengisi_id', 
        'materi_pembelajaran', 
        'catatan_tambahan', 
        'status_validasi', 
        'catatan_kepsek'
    ];

    // Konversi tipe data otomatis (Casting)
    protected function casts(): array
    {
        return [
            'tanggal_mengajar' => 'date',
        ];
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    // Mengambil data siapa guru yang mengisi (berguna jika ada inval)
    public function guruPengisi()
    {
        return $this->belongsTo(Guru::class, 'guru_pengisi_id');
    }
}