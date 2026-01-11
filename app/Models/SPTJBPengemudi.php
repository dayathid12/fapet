<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SPTJBPengemudi extends Model
{
    protected $table = 'sptjb_pengemudis';

    protected $fillable = [
        'no_sptjb',
        'uraian',
        'penerima',
        'total_jumlah_uang_diterima',
    ];

    protected $appends = [
        'total_jumlah_uang_diterima',
    ];

    public function details()
    {
        return $this->hasMany(SPTJBUangPengemudiDetail::class, 'sptjb_pengemudi_id');
    }

    public function getTotalJumlahUangDiterimaAttribute()
    {
        return $this->details()->sum(\DB::raw('besaran_uang_per_hari * jumlah_hari'));
    }
}
