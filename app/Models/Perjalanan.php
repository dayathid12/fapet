<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Perjalanan extends Model
{
    use HasFactory;

    protected $primaryKey = 'nomor_perjalanan';

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
    ];

    protected $attributes = [
        'status_operasional' => 'Belum Ditetapkan',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->no_surat_tugas)) {
                $nextNumber = static::max('nomor_perjalanan') + 1;
                $model->no_surat_tugas = 'ST-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT) . '/' . date('Y');
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
}
