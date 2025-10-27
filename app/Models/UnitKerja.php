<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;

    protected $primaryKey = 'unit_kerja_id';

    protected $fillable = [
        'nama_unit_kerja',
    ];
}
