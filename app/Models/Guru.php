<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = ['user_id', 'nip', 'nama_lengkap'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    // Relasi untuk guru pengganti (inval)
    public function jurnals()
    {
        return $this->hasMany(JurnalGuru::class, 'guru_pengisi_id');
    }
}