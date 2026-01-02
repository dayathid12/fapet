<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perjalanan extends Model
{
    use HasFactory;


    protected $fillable = [
        'nomor_perjalanan',
        'waktu_keberangkatan',
        'waktu_kepulangan',
        'status_perjalanan',
        'alamat_tujuan',
        'lokasi_keberangkatan',
        'jumlah_rombongan',
        'jenis_kegiatan',
        'nama_kegiatan',
        'jenis_operasional',
        'status_operasional',
        'no_surat_tugas',
        'file_surat_jalan',
        'docs_surat_tugas',
        'upload_surat_tugas',
        'download_file',
        'surat_peminjaman_kendaraan',
        'surat_izin_kegiatan',
        'dokumen_pendukung',
        'status_cek_1',
        'status_cek_2',
        'nama_pengguna',
        'kontak_pengguna',
        'nama_personil_perwakilan',
        'kontak_pengguna_perwakilan',
        'status_sebagai',
        'provinsi',
        'uraian_singkat_kegiatan',
        'catatan_keterangan_tambahan',
        'token',
        'tujuan_wilayah_id',
        'unit_kerja_id',
        'entry_pengeluaran_id', // Menambahkan ini agar bisa di-fill
    ];

    protected $casts = [
        'waktu_keberangkatan' => 'datetime',
        'waktu_kepulangan' => 'datetime',
    ];


    protected $attributes = [
        'status_operasional' => 'Belum Ditetapkan',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            // Get the next ID
            $nextId = (static::max('id') ?? 0) + 1;

            if (empty($model->nomor_perjalanan)) {
                $model->nomor_perjalanan = str_pad($nextId, 3, '0', STR_PAD_LEFT) . '|' . date('my');
            }
            if (empty($model->token)) {
                $model->token = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id', 'unit_kerja_id');
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'tujuan_wilayah_id', 'wilayah_id');
    }

    /**
     * Get all of the details for the Perjalanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(PerjalananKendaraan::class, 'perjalanan_id');
    }

    /**
     * Get the kendaraan for the Perjalanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function kendaraan()
    {
        return $this->hasManyThrough(Kendaraan::class, PerjalananKendaraan::class, 'perjalanan_id', 'nopol_kendaraan', 'id', 'kendaraan_nopol');
    }

    /**
     * Get the pengemudi for the Perjalanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function pengemudi()
    {
        return $this->hasManyThrough(Staf::class, PerjalananKendaraan::class, 'perjalanan_id', 'staf_id', 'id', 'pengemudi_id');
    }

    /**
     * Get the asisten for the Perjalanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function asisten()
    {
        return $this->hasManyThrough(Staf::class, PerjalananKendaraan::class, 'perjalanan_id', 'staf_id', 'id', 'asisten_id');
    }

    public function entryPengeluaran(): BelongsTo
    {
        return $this->belongsTo(EntryPengeluaran::class, 'entry_pengeluaran_id');
    }

    public function getDynamicStatusAttribute(): string
    {
        if ($this->status_perjalanan === 'Terjadwal' && $this->waktu_kepulangan && $this->waktu_kepulangan->isPast()) {
            return 'Selesai';
        }

        return $this->status_perjalanan;
    }

}
