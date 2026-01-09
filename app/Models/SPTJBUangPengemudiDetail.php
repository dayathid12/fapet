<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SPTJBUangPengemudiDetail extends Model
{
    protected $table = 'sptjb_uang_pengemudi_details';

    protected $fillable = [
        'sptjb_pengemudi_id',
        'no',
        'nama',
        'jabatan',
        'tanggal_penugasan',
        'jumlah_hari',
        'besaran_uang_per_hari',
        'jumlah_rp',
        'jumlah_uang_diterima',
        'nomor_rekening',
        'golongan',
        'no_sptjb',
        'nomor_perjalanan',
    ];

    protected $casts = [
        'tanggal_penugasan' => 'date',
        'besaran_uang_per_hari' => 'decimal:2',
        'jumlah_rp' => 'decimal:2',
        'jumlah_uang_diterima' => 'decimal:2',
    ];

    public function sptjbPengemudi(): BelongsTo
    {
        return $this->belongsTo(SPTJBPengemudi::class, 'sptjb_pengemudi_id');
    }
}
