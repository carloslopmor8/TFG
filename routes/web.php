<?php

use App\Http\Controllers\CantidadController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProyectoUsuariosController;
use App\Http\Controllers\ProyectoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Redirect::route('dashboard');
});

/* Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); */

Route::get('/dashboard', [ProyectoController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/proyecto/create', [ProyectoController::class, 'create'])->middleware(['auth', 'verified'])->name('proyecto.create');
Route::post('/proyecto/save', [ProyectoController::class, 'save'])->middleware(['auth', 'verified'])->name('proyecto.save');
Route::get('/proyecto/show/{id}', [ProyectoController::class, 'show'])->middleware(['auth', 'verified'])->name('proyecto.show');
Route::delete('/proyecto/delete/{id}', [ProyectoController::class, 'delete'])->middleware(['auth', 'verified'])->name('proyecto.delete');
Route::get('/proyecto/{id}/edit', [ProyectoController::class, 'edit'])->middleware(['auth', 'verified'])->name('proyecto.edit');
Route::post('/proyecto/{id}/update', [ProyectoController::class, 'update'])->middleware(['auth', 'verified'])->name('proyecto.update');

//Etiquetas
Route::get('/proyecto/{id}/edit/etiquetas', [EtiquetaController::class, 'edit'])->middleware(['auth', 'verified'])->name('proyecto.etiquetas.edit');
Route::post('/proyecto/{id}/add-etiqueta', [EtiquetaController::class, 'save'])->middleware(['auth', 'verified'])->name('proyecto.add.etiqueta');

//Cantidades
Route::get('/proyecto/{id}/edit/cantidades', [CantidadController::class, 'edit'])->middleware(['auth', 'verified'])->name('proyecto.cantidades.edit');
Route::post('/proyecto/{id}/cantidad', [CantidadController::class, 'save'])->middleware(['auth', 'verified'])->name('proyecto.save.cantidad');

Route::get('/edit/{id}/gasto', [CantidadController::class, 'update'])->middleware(['auth', 'verified'])->name('proyecto.cantidades.update');
Route::post('/update/{id}/gasto', [CantidadController::class, 'updatesave'])->middleware(['auth', 'verified'])->name('proyecto.cantidad.save');
Route::delete('/delete/{id}/gasto', [CantidadController::class, 'delete'])->middleware(['auth', 'verified'])->name('proyecto.gasto.delete');

//Cantidades
Route::get('/proyecto/{id}/edit/usuarios', [ProyectoUsuariosController::class, 'index'])->middleware(['auth', 'verified'])->name('proyecto.usuarios.index');
Route::post('/proyecto/{id}/usuarios', [ProyectoUsuariosController::class, 'save'])->middleware(['auth', 'verified'])->name('proyecto.add.usuario');
Route::get('/proyecto/{id}/edit/usuario/{usuario}', [ProyectoUsuariosController::class, 'edit'])->middleware(['auth', 'verified'])->name('proyecto.usuarios.edit');
Route::post('/proyecto/{id}/edit/usuario/{usuario}', [ProyectoUsuariosController::class, 'save_permissions'])->middleware(['auth', 'verified'])->name('proyecto.usuarios.permissions.save');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
