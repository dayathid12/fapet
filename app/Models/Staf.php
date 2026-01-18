<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staf extends Model
{
    use HasFactory;

    protected $primaryKey = 'staf_id';

    protected $fillable = [
        'id_nama',
        'nama_staf',
        'gol_pangkat',
        'nip_staf',
        'status',
        'pendidikan_aktif',
        'wa_staf',
        'jabatan',
        'tanggal_lahir',
        'kartu_pegawai',
        'status_kepegawaian',
        'tempat_lahir',
        'no_ktp',
        'no_npwp',
        'no_bpjs_kesehatan',
        'no_bpjs_ketenagakerjaan',
        'no_telepon',
        'email',
        'alamat_rumah',
        'rekening',
        'nama_bank',
        'status_aplikasi',
        'sort_order',
    ];

    protected $appends = [
        'menuju_pensiun',
    ];

    public function perjalanans()
    {
        return $this->belongsToMany(Perjalanan::class, 'perjalanan_kendaraans', 'pengemudi_id', 'perjalanan_id', 'staf_id', 'nomor_perjalanan')
                    ->withPivot('kendaraan_nopol', 'asisten_id');
    }

    /**
     * Get all of the jadwalPengemudis for the Staf
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jadwalPengemudis(): HasMany
    {
        return $this->hasMany(JadwalPengemudi::class, 'staf_id', 'staf_id');
    }

    /**
     * Get the retirement countdown attribute.
     *
     * @return string
     */
    public function getMenujuPensiunAttribute(): string
    {
        if (!$this->tanggal_lahir) {
            return 'Data tanggal lahir tidak tersedia';
        }

        try {
            $birthDate = \Carbon\Carbon::parse($this->tanggal_lahir);
            $retirementDate = $birthDate->copy()->addYears(58); // PNS pensiun di usia 58 tahun
            $now = \Carbon\Carbon::now();

            if ($retirementDate->isPast()) {
                return 'Sudah pensiun';
            }

            $diff = $now->diff($retirementDate);

            $years = $diff->y;
            $months = $diff->m;
            $days = $diff->d;

            $parts = [];

            if ($years > 0) {
                $parts[] = $years . ' tahun';
            }

            if ($months > 0) {
                $parts[] = $months . ' bulan';
            }

            if ($days > 0) {
                $parts[] = $days . ' hari';
            }

            return implode(' ', $parts) ?: 'Kurang dari 1 hari';

        } catch (\Exception $e) {
            return 'Error menghitung waktu pensiun';
        }
    }
}

