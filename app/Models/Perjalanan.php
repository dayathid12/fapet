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
        'status_cek_1',
        'status_cek_2',
        'pengguna_id',
        'pengemudi_id',
        'asisten_id',
        'nopol_kendaraan',
        'tujuan_wilayah_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if ($model->getKey() === null) {
                $model->setAttribute($model->getKeyName(), static::max($model->getKeyName()) + 1);
            }
        });
    }
}