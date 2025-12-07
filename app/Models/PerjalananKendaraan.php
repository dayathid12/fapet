<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerjalananKendaraan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'perjalanan_id',
        'kendaraan_nopol',
        'pengemudi_id',
        'asisten_id',
    ];

    /**
     * Get the perjalanan that owns the PerjalananKendaraan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function perjalanan(): BelongsTo
    {
        return $this->belongsTo(Perjalanan::class, 'perjalanan_id', 'nomor_perjalanan');
    }

    /**
     * Get the kendaraan that owns the PerjalananKendaraan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_nopol', 'nopol_kendaraan');
    }

    /**
     * Get the pengemudi for the PerjalananKendaraan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengemudi(): BelongsTo
    {
        return $this->belongsTo(Staf::class, 'pengemudi_id', 'staf_id');
    }

    /**
     * Get the asisten for the PerjalananKendaraan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asisten(): BelongsTo
    {
        return $this->belongsTo(Staf::class, 'asisten_id', 'staf_id');
    }
}