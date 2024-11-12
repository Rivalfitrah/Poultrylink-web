<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\User;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $fillable = [
        'total',
        'harga',
        'user_id',
        'confirmed',
    ];

    // Relasi ke OrderDetail
    public function details()
    {
        return $this->hasMany(Orderdetail::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
