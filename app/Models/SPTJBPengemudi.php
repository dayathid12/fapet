<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SPTJBPengemudi extends Model
{
    protected $table = 'sptjb_pengemudis';

    protected $fillable = [
        'no_sptjb',
        'uraian',
        'penerima',
    ];
}
