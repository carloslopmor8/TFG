<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioGasto extends Model
{
    use HasFactory;

    protected $fillable = [
        'gasto_id',
        'usuario_id',
        'cantidad',
    ];

    public function gasto()
    {
        return $this->belongsTo(Gasto::class);
    }
}
