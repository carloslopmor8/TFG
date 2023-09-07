<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoCantidad extends Model
{
    use HasFactory;

    protected $table = 'proyecto_cantidades';

    protected $fillable = [
        'proyecto_id',
        'etiqueta_id',
        'cantidad',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function etiqueta()
    {
        return $this->belongsTo(ProyectoEtiqueta::class, 'etiqueta_id');
    }
}
