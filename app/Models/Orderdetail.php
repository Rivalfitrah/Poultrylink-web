<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;

class Orderdetail extends Model
{
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'produk_id',
        'supplier_id',
        'quantity',
        'price',
        'total_price'
    ];

        // Relasi ke model Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Relasi ke model Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
