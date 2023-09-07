<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\ProyectoEtiqueta;
use App\Models\ProyectoUsuario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProyectoUsuariosController extends Controller
{
    public function index(Request $request, $id)
    {

        $user = auth()->user();
        
        if (!$user->proyectos_admin()->contains('id',$id)) {
            abort(403, 'Unauthorized action.');
        }

        $proyecto = Proyecto::with('usuarios.user')->find($id);

        return Inertia::render('Proyecto/Usuarios/Edit', [
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
            'usuario' => ['required', 'email'],
        ]);

        $proyecto = Proyecto::find($id);

        $usuario_encontrado = User::where('email', $request->usuario)->first();

        if($usuario_encontrado){

            if($usuario_encontrado->id === $user->id){
                session()->flash('error', 'No asignarte a ti mismo.');
                return Inertia::location(route('proyecto.usuarios.index', ['id' => $id]));
            }

            ProyectoUsuario::create([
                'proyecto_id' => $proyecto->id,
                'user_id' => $usuario_encontrado->id,
                'is_admin' => false,
            ]);

            session()->flash('success', 'Usuario añadido correctamente');
            return Inertia::location(route('proyecto.usuarios.index', ['id' => $id]));

        }else{

            session()->flash('error', 'No se encontró ningún usuario registrado con ese correo');
            return Inertia::location(route('proyecto.usuarios.index', ['id' => $id]));

        }
        
        return Inertia::location(route('proyecto.usuarios.index', ['id' => $id]));
    }

    public function edit(Request $request, $id, $usuario)
    {
        $user = auth()->user();
        
        if (!$user->proyectos_admin()->contains('id',$id)) {
            abort(403, 'Unauthorized action.');
        }

        $proyecto = Proyecto::find($id);

        $usuario = ProyectoUsuario::with('user')->find($usuario);

        $permissionColumns = [
            'is_admin',
        ];
        
        // Loop through the permission columns and convert the 1s and 0s to true and false
        foreach ($permissionColumns as $column) {
            // Convert the value from 1 to true, or 0 to false
            $usuario->$column = (bool) $usuario->$column;
        }

        return Inertia::render('Proyecto/Usuarios/Show', [
            'proyecto' => $proyecto,
            'usuario' => $usuario,
        ]);
    }

    public function save_permissions(Request $request, $id, $usuario)
    {

        $user = auth()->user();
        
        if (!$user->proyectos_admin()->contains('id',$id)) {
            abort(403, 'Unauthorized action.');
        }

        $proyecto = Proyecto::find($id);

        $usuario = ProyectoUsuario::with('user')->find($usuario);

        $request->validate([
            'is_admin' => ['required', 'boolean'],
           
        ]);

        $usuario->is_admin = $request->is_admin;
       
        $usuario->save();

        session()->flash('success', 'Permisos actualizados correctamente');

        return Redirect::route('proyecto.usuarios.edit', ['id' => $id, 'usuario' => $usuario->id]);
    }
}
