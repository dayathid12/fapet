<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalMengemudi extends Model
{
    protected $table = 'jadwal_mengemudis';

    protected $fillable = [
        'perjalanan_id',
        'pengemudi_id',
        'asisten_id',
        'kendaraan_id',
        'tipe_penugasan',
        'waktu_keberangkatan',
        'waktu_kepulangan',
        'waktu_selesai_penugasan',
        'status',
        'catatan',
    ];

    protected $casts = [
        'waktu_keberangkatan' => 'datetime',
        'waktu_kepulangan' => 'datetime',
        'waktu_selesai_penugasan' => 'datetime',
    ];

    public function perjalanan()
    {
        return $this->belongsTo(Perjalanan::class);
    }

    public function pengemudi()
    {
        return $this->belongsTo(Staf::class, 'pengemudi_id');
    }

    public function asisten()
    {
        return $this->belongsTo(Staf::class, 'asisten_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
}
