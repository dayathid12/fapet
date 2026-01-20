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
        'tipe_penugasan', // Ini juga perlu ditambahkan jika belum ada
        'waktu_selesai_penugasan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'waktu_selesai_penugasan' => 'datetime',
    ];

    /**
     * Get the perjalanan that owns the PerjalananKendaraan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function perjalanan(): BelongsTo
    {
        return $this->belongsTo(Perjalanan::class);
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

        public function rincianPengeluarans(): \Illuminate\Database\Eloquent\Relations\HasMany
        {
            return $this->hasMany(RincianPengeluaran::class, 'perjalanan_id');
        }

        public function hasBeenProcessed(): bool
        {
            if (!$this->perjalanan || !$this->perjalanan->no_surat_tugas) {
                return false;
            }

            return \App\Models\SPTJBUangPengemudiDetail::where('nomor_surat', $this->perjalanan->no_surat_tugas)->exists();
        }
    }
