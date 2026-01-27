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
    ];

    protected $casts = [
        'tanggal_usulan' => 'date',
        'total' => 'decimal:2',
        'upload_files' => 'array',
        'proses_pr_screenshots' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->nomor_ajuan)) {
                $model->nomor_ajuan = 'PR-' . str_pad((static::count() + 1), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
