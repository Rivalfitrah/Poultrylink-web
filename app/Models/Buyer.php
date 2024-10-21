<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buyer extends Model
{
    
    use HasFactory, SoftDeletes;

    protected $table = 'buyer';
    protected $fillable = [
        'firstname',
        'lastname',
        'alamat',
        'telepon',
        'kota',
        'kodepos',
        'provinsi',
        'negara',
        'user_id'
    ];

    public function userGet() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ulasanGet(){
        return $this->hasMany(Ulasan::class, 'user_id', 'user_id'); // Relasi melalui user_id
    }
}
