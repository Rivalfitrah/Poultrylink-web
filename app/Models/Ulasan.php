<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';

    protected $fillable = [
        'produk_id',
        'user_id',
        'ulasan'
    ];

    public function userGet(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    
}
