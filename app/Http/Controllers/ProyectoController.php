<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Gasto;
use App\Models\Movimiento;
use App\Models\Proyecto;
use App\Models\ProyectoCantidad;
use App\Models\ProyectoEtiqueta;
use App\Models\ProyectoUsuario;
use App\Models\User;
use App\Models\UsuarioGasto;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProyectoController extends Controller
{

    public function index(Request $request): Response
    {
        Log::info($request);
        $user = auth()->user();

        $proyectos = $user->proyectos_asignados();

        if ($request->search) {
            Log::info($proyectos);
            $proyectos = $proyectos->filter(function ($proyecto) use ($request) {
                return stripos($proyecto['nombre'], $request->search) !== false;
            });
            
            Log::info($proyectos);
            Log::info($request->search);
        }
        

        // Loop through the $proyectos array and update the image_url parameter
        foreach ($proyectos as $proyecto) {
            // Concatenate the base URL with the existing image_url
            $proyecto->imagen_url = env('APP_URL') . $proyecto->imagen_url;
        }

        return Inertia::render('Dashboard', [
            'proyectos' => $proyectos,
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Proyecto/Create');
    }

    public function save(Request $request)
    {
        Log::info($request);

        $request->validate([
            'nombre' => ['required', 'string'],
            'descripcion' => ['required', 'string'],
            /* 'imagen_url' => ['required'], */
            'presupuesto' =>  ['required','numeric','gt:0'],
        ]);

        $path = '';

        if ($request->hasFile('imagen_url')) {
            $file = $request->file('imagen_url');
            $fileName = $file->getClientOriginalName();
            $path = 'public/files/' . $fileName;

            Storage::disk('local')->put($path, file_get_contents($file));
        }

        $user = auth()->user();

        $proyecto = Proyecto::create([
            'user_id' => $user->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'imagen_url' => 'storage/files/' . $fileName,
            'presupuesto' => $request->presupuesto,
        ]);

        return Redirect::route('dashboard');
    }

    public function show(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->proyectos_asignados()->contains('id', $id)) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->proyectos_admin()->contains('id', $id)) {
            $user->is_admin = true;
        } else {
            $user->is_admin = false;
        }

        $proyecto = Proyecto::with('gastos', 'usuarios.user', 'user')->find($id);

        $movimientos = Movimiento::where('proyecto_id', $id)->with('user')->orderBy('created_at', 'desc')->get();


        $proyecto->imagen_url = env('APP_URL') . $proyecto->imagen_url;


        
       

        return Inertia::render('Proyecto/Show', [
            'proyecto' => $proyecto,
            'usuario' => $user,
            'movimientos' => $movimientos,
        ]);
    }

    public function delete(Request $request, $id)
    {
        $user = auth()->user();

        $proyecto = Proyecto::find($id);

        // Check if the proyecto exists and belongs to the logged-in user
        if (!$proyecto || $proyecto->user_id !== $user->id) {
            abort(404); // Or handle the unauthorized action as needed
        }
        $gastos = Gasto::where('proyecto_id', $proyecto->id)->get();

        foreach($gastos as $gasto){
            UsuarioGasto::where('gasto_id', $gasto->id)->delete();
        }
        Gasto::where('proyecto_id', $proyecto->id)->delete();
        Movimiento::where('proyecto_id', $proyecto->id)->delete();
        ProyectoUsuario::where('proyecto_id', $proyecto->id)->delete();
        // Perform the deletion
        $proyecto->delete();

        return redirect()->route('dashboard'); // Redirect to the dashboard after deletion
    }

    public function edit(Request $request, $id): Response
    {
        $proyecto = Proyecto::find($id);

        return Inertia::render('Proyecto/Edit', [
            'proyecto' => $proyecto,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => ['required', 'string'],
            'descripcion' => ['required', 'string'],
            'presupuesto' => ['numeric'],
        ]);

        $user = auth()->user();

        $proyecto = Proyecto::find($id);

        $proyecto->nombre = $request->nombre;
        $proyecto->descripcion = $request->descripcion;



        if ($request->presupuesto < $proyecto->gastado) {
            session()->flash('error', 'Ya se ha gastado una cantidad mayor a la que tratas de asignar a este proyecto.');
            return Inertia::location(route('proyecto.edit', ['id' => $id]));
        }


        $proyecto->presupuesto = $request->presupuesto;

        $proyecto->save();

        return Redirect::route('dashboard');
    }
}
