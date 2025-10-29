<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'menuju_pensiun',
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
    ];
}
