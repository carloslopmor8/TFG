<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\Object_;

class Proyecto extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'imagen_url',
        'presupuesto',
    ];

    protected $appends = ['gastado', 'restante', 'chart', 'user_chart'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function etiquetas()
    {
        return $this->hasMany(ProyectoEtiqueta::class);
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

    public function usuarios()
    {
        return $this->hasMany(ProyectoUsuario::class);
    }

    public function getGastadoAttribute()
    {
        $gastado = $this->gastos->sum('cantidad');
        return $gastado;
    }

    public function getRestanteAttribute()
    {
        $gastado = $this->gastos->sum('cantidad');
        $restante = $this->presupuesto - $gastado;
        return $restante;
    }

    public function getChartAttribute()
    {
        $info = [['Daily Routine', 'Hours per Day']];
    
        foreach($this->gastos as $gasto){
            $new_value = [$gasto->nombre, $gasto->cantidad];
            array_push($info, $new_value);
        }

        $new_value = ['Restante', $this->restante];
        array_push($info, $new_value);

        return $info;
    }

    public function getUserChartAttribute()
    {
        $info = [['Daily Routine', 'Dinero gastado']];

        $usuarios = $this->usuarios;
        $users = collect();
        foreach ($usuarios as $usuario) {
            $new_value = $usuario->user;
            $users->push($new_value);
        }
        
        $usuario_creador = $this->user;

        $concatenated = $users->concat([$usuario_creador]); // Wrap $usuario_creador in an array

        $gastos_ids = $this->gastos->pluck('id')->toArray();

        foreach($concatenated as $user){
            
            $gastos = UsuarioGasto::whereIn('gasto_id', $gastos_ids)->where('usuario_id', $user->id)->sum('cantidad');
            $new_value = [$user->name,$gastos ];
            array_push($info, $new_value);
        }

        return $info;
    }

    public function gastos()
    {
        return $this->hasMany(Gasto::class);
    }

    public function user_gastos()
    {
        return $this->hasMany(Gasto::class); // Assuming you have a Gasto model
    }

}
