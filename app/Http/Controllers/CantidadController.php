<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Movimiento;
use App\Models\Proyecto;
use App\Models\ProyectoCantidad;
use App\Models\ProyectoEtiqueta;
use App\Models\User;
use App\Models\UsuarioGasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CantidadController extends Controller
{
    public function edit(Request $request, $id)
    {

        $user = auth()->user();

        if (!$user->proyectos_admin()->contains('id', $id)) {
            abort(403, 'Unauthorized action.');
        }

        $proyecto = Proyecto::with('gastos', 'usuarios.user', 'user')->find($id);

        return Inertia::render('Proyecto/Cantidades/Edit', [
            'proyecto' => $proyecto,
        ]);
    }

    public function save(Request $request, $id)
    {

        $user = auth()->user();
        if (!$user->proyectos_admin()->contains('id', $id)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'nombre' => ['required', 'string'],
            'anadir_iva' => ['required', 'boolean'],
            'cantidad' => ['required','numeric','gt:0'],
            'quien' => ['required', 'array'],
        ]);

        $proyecto = Proyecto::find($id);

        $usuario = auth()->user();


        //Comprobar si en el presupuesto restante hay suficiente candidad para sumar
        if ($proyecto->restante < $request->cantidad) {
            session()->flash('error', 'No hay suficiente presupuesto restante para añadir este gasto.');
            return Inertia::location(route('proyecto.cantidades.edit', ['id' => $id]));
        } else {

            $autor = User::find($request->quien);

            $gasto = Gasto::create([
                'proyecto_id' => $proyecto->id,
                'id_factura' => $request->factura_id,
                'nombre' => $request->nombre,
                'observacion' => $request->observacion,
                'anadir_iva' => $request->anadir_iva,
                'cantidad' => $request->cantidad,
                'cantidad_iva' => ($request->anadir_iva == True ? $request->cantidad*1.21 : 0),
                'created_at' =>$request->fecha
            ]);

            foreach($request->quien as $quien_id){
/*                 $user = User::find($id);
 */
                UsuarioGasto::create([
                    'gasto_id' => $gasto->id,
                    'usuario_id' => $quien_id,
                    'cantidad' => $request->cantidad / count($request->quien),
                ]);

            }

            $movimiento = Movimiento::create([
                'proyecto_id' => $proyecto->id,
                'user_id' => 1,
                'valor' => 'Ha gastado ' . $request->cantidad . '$ en ' . $gasto->nombre ,
            ]);

            session()->flash('success', 'Gasto añadido correctamente');

            return Inertia::location(route('proyecto.cantidades.edit', ['id' => $id]));
        }
    }

    public function update(Request $request,Gasto $id)
    {
        Log::info($request);
        Log::info($id);

        $user = auth()->user();

       /*  if (!$user->proyectos_admin()->contains('id', $id)) {
            abort(403, 'Unauthorized action.');
        } */

        $proyecto = Proyecto::with('gastos', 'usuarios.user', 'user')->find($id->proyecto->id);
        
        $id->load('usuarios');

        return Inertia::render('Proyecto/Cantidades/Update', [
            'proyecto' => $proyecto,
            'gasto' => $id,
        ]);
    }


    public function updatesave(Request $request,   Gasto $id)
    {

        $user = auth()->user();
        /* if (!$user->proyectos_admin()->contains('id', $id)) {
            abort(403, 'Unauthorized action.');
        } */

       /*  $request->validate([
            'id' => ['required', 'numeric'],
            'factura_id' => ['required', 'string'],
            'nombre' => ['required', 'string'],
            'observacion' => ['required', 'string'],
            'anadir_iva' => ['required', 'boolean'],
            'cantidad' => ['required', 'numeric'],
            'quien' => ['required', 'array'],
        ]); */

        $request->validate([
            'nombre' => ['required', 'string'],
            'anadir_iva' => ['required', 'boolean'],
            'cantidad' => ['required','numeric','gt:0'],
            'quien' => ['required', 'array'],
        ]);

        $gasto = $id;

        $proyecto = $id->proyecto;

        //Comprobar si en el presupuesto restante hay suficiente candidad para sumar
        if ($proyecto->restante + $gasto->cantidad < $request->cantidad) {
            session()->flash('error', 'No hay suficiente presupuesto restante para añadir este gasto.');
            return Inertia::location(route('proyecto.cantidades.edit', ['id' => $id]));
        } else {

            $autor = User::find($request->quien);

            $gasto->id_factura = $request->factura_id;
            $gasto->nombre = $request->nombre;
            $gasto->observacion = $request->observacion;
            $gasto->anadir_iva = $request->anadir_iva;
            $gasto->cantidad = $request->cantidad;
            $gasto->cantidad_iva = ($request->anadir_iva == True ? $request->cantidad*1.21 : 0);
            $gasto->save();

            UsuarioGasto::where('gasto_id', $id->id)->delete();

            foreach($request->quien as $quien_id){
/*                 $user = User::find($id);
 */
                UsuarioGasto::create([
                    'gasto_id' => $gasto->id,
                    'usuario_id' => $quien_id,
                    'cantidad' => $request->cantidad / count($request->quien),
                ]);

            }

            $movimiento = Movimiento::create([
                'proyecto_id' => $gasto->proyecto->id,
                'user_id' => 1,
                'valor' => 'Ha actualizado el gasto ' . $gasto->nombre ,
            ]);

            session()->flash('success', 'Gasto actualizado correctamente');

            return Inertia::location(route('proyecto.show', ['id' => $proyecto->id]));
        }
    }

    public function delete(Request $request,Gasto $id)
    {
        $user = auth()->user();

        $proyecto = Proyecto::with('gastos')->find($id->proyecto->id);

        // Check if the proyecto exists and belongs to the logged-in user
        if (!$proyecto || $proyecto->user_id !== $user->id) {
            abort(404); // Or handle the unauthorized action as needed
        }

        UsuarioGasto::where('gasto_id', $id->id)->delete();
        // Perform the deletion
        $id->delete();

        return redirect()->route('proyecto.show', ['id' => $proyecto->id]); // Redirect to the dashboard after deletion
    }
}
