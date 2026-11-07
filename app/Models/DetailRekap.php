<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailRekap extends Model
{
    protected $table = 'detail_rekap';

    protected $fillable = [
        'rekap_id',
        'pegawai_id',
        'status',
        'keterangan',
    ];

    public function rekap()
    {
        return $this->belongsTo(Rekap::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
