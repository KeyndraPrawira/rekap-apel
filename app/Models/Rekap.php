<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekap extends Model
{
    protected $table = 'rekap';

    protected $fillable = [
        'jenis_apel_id',
        'pegawai_id',
        'tanggal',
    ];

    public function jenisApel()
    {
        return $this->belongsTo(JenisApel::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function detailRekap()
    {
        return $this->hasMany(DetailRekap::class);
    }
}
