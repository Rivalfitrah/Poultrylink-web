<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Atmin extends Model
{
    
    use HasFactory, SoftDeletes, HasApiTokens;

    protected $table = 'atmin';
    protected $fillable = [
        'name',
        'username',
        'password',
    ];
}
