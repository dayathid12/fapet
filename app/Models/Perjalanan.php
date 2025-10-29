<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'pengemudi_id',
        'asisten_id',
        'nopol_kendaraan',
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
            if ($model->getKey() === null) {
                $model->setAttribute($model->getKeyName(), static::max($model->getKeyName()) + 1);
            }
            if (empty($model->no_surat_tugas)) {
                $nextNumber = static::max('nomor_perjalanan') + 1;
                $model->no_surat_tugas = 'ST-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT) . '/' . date('Y');
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

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'nopol_kendaraan', 'nopol_kendaraan');
    }

    public function pengemudi()
    {
        return $this->belongsTo(Staf::class, 'pengemudi_id', 'staf_id');
    }

    public function asisten()
    {
        return $this->belongsTo(Staf::class, 'asisten_id', 'staf_id');
    }

}
