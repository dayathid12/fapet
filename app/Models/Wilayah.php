<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $primaryKey = 'wilayah_id';

    protected $fillable = [
        'nama_wilayah',
        'kota_kabupaten',
        'provinsi',
    ];
}
