<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianBiaya extends Model
{
    use HasFactory;

    protected $fillable = [
        'rincian_pengeluaran_id',
        'tipe',
        'deskripsi',
        'volume',
        'jenis_bbm',
        'biaya',
        'bukti_path',
        'pertama_retail',
    ];
}
