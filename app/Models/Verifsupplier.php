<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifsupplier extends Model
{
    use HasFactory;

    protected $table = 'verificationsupplier';

    protected $fillable = [
        'imageKtp',
        'imageNPWP',
        'nik',
        'nama',
        'ttl',
        'alamat',
        'kewarganegaraan',
    ];


}
