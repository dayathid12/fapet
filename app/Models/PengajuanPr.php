<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPr extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perkerjaan',
        'tanggal_usulan',
        'total',
        'upload_files',
        'nomor_pr',
        'proses_pr_screenshots',
        'nomor_ajuan',
        'tanggal_proses_pr_screenshots',
    ];

    protected $casts = [
        'tanggal_usulan' => 'datetime',
        'total' => 'decimal:2',
        'upload_files' => 'array',
        'proses_pr_screenshots' => 'array',
        'tanggal_proses_pr_screenshots' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->nomor_ajuan)) {
                $model->nomor_ajuan = str_pad((static::count() + 1), 4, '0', STR_PAD_LEFT);
            }
            if (empty($model->tanggal_usulan)) {
                $model->tanggal_usulan = now();
            }
            // Set tanggal_proses_pr_screenshots otomatis jika ada proses_pr_screenshots saat create
            if (!empty($model->proses_pr_screenshots) && empty($model->tanggal_proses_pr_screenshots)) {
                $model->tanggal_proses_pr_screenshots = now();
            }
        });

        static::updating(function ($model) {
            // Set tanggal_proses_pr_screenshots otomatis jika proses_pr_screenshots berubah
            if ($model->isDirty('proses_pr_screenshots')) {
                $model->tanggal_proses_pr_screenshots = now();
            }
        });
    }
}
