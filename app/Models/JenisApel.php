<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisApel extends Model
{
    protected $table = 'jenis_apel';

    protected $fillable = [
        'nama_jenis',
    ];

    public function rekap()
    {
        return $this->hasMany(Rekap::class);
    }
}
