<?php

namespace App\Models;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'rating',
        'kategori_id',
        'supplier_id',
        'harga',
        'jumlah',
        'image',
    ];

    public function getSupplier() {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
}
