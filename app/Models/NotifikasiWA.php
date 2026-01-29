<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifikasiWA extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor',
        'judul',
        'isi_pesan',
        'number_key',
    ];
}
