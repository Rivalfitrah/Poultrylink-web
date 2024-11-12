<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    protected $fillable = [
        'id',
        'namatoko',
        'alamat',
        'kota',
        'kodepos',
        'provinsi',
        'negara',
        'deskripsi',
        'rating',
        'user_id',
    ];

    public function userGet() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
