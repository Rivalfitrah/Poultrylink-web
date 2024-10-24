<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'total_harga',
        'total_barang',
        'produk_id',
    ];

    public function userGet(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function produkGet(){
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }

    public function totalharga(){
        
    }
}
