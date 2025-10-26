<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPTJB extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_sptjb';
    public $incrementing = false;
    protected $keyType = 'string';
}
