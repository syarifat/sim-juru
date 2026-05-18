<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $fillable = ['tahun', 'semester', 'status_aktif'];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}