<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $primaryKey = 'nopol_kendaraan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nopol_kendaraan',
        'jenis_kendaraan',
        'merk_type',
        'warna_tanda',
        'tahun_pembuatan',
        'nomor_rangka',
        'nomor_mesin',
        'jenis_bbm_default',
        'lokasi_kendaraan',
        'penggunaan',
        'foto_kendaraan',
        'sort_order',
    ];

    public function perjalanans()
    {
        return $this->hasMany(Perjalanan::class, 'nopol_kendaraan', 'nopol_kendaraan');
    }

    public function perjalananKendaraans()
    {
        return $this->hasMany(PerjalananKendaraan::class, 'kendaraan_nopol', 'nopol_kendaraan');
    }
}
