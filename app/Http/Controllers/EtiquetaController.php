<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\ProyectoCantidad;
use App\Models\ProyectoEtiqueta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EtiquetaController extends Controller
{

    public function edit(Request $request, $id)
    {
        $user = auth()->user();
        
        if (!$user->proyectos_admin()->contains('id',$id)) {
            abort(403, 'Unauthorized action.');
        }

        $proyecto = Proyecto::with('etiquetas')->find($id);

        return Inertia::render('Proyecto/Etiquetas/Edit', [
            'proyecto' => $proyecto,
        ]);
    }

    public function save(Request $request, $id)
    {

        $user = auth()->user();
        
        if (!$user->proyectos_admin()->contains('id',$id)) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'etiqueta' => ['required'],
        ]);

        $etiqueta = ProyectoEtiqueta::create([
            'proyecto_id' => $id,
            'etiqueta' => $request->etiqueta,
            'cantidad' => 0.0

        ]);

        return Inertia::location(route('proyecto.etiquetas.edit', ['id' => $id]));
    }
}
