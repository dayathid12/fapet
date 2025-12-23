<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KondisiBan extends Model
{
    use HasFactory;

    protected $table = 'kondisi_bans';

    protected $fillable = [
        'nopol_kendaraan',
        'ban_depan_kiri',
        'ban_depan_kanan',
        'ban_belakang_kiri',
        'ban_belakang_kanan',
        'odo_terbaru',
    ];

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'nopol_kendaraan', 'nopol_kendaraan');
    }
}