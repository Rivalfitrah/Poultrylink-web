<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Buyer extends Model
{
    
    use HasFactory;

    protected $table = 'buyer';
    protected $fillable = [
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
}
