<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'nama',
        'jabatan',
        'no_hp',
        'email',
    ];

    public function rekap()
    {
        return $this->hasMany(Rekap::class);
    }

    public function detailRekap()
    {
        return $this->hasMany(DetailRekap::class);
    }
}
