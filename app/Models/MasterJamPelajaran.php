<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJamPelajaran extends Model
{
    protected $fillable = ['hari', 'jam_ke', 'jam_mulai', 'jam_selesai', 'keterangan'];

    // Catatan: Model ini tidak direlasikan langsung ke Jadwal via Foreign Key,
    // melainkan digunakan sebagai referensi pencarian waktu (where jam_ke).
}