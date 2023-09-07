<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'valor',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
