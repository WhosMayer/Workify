<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas (Breeze auth)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Rutas protegidas de la aplicación (tu UI bonita + Breeze auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard principal (el bonito con estadísticas y gráficos)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Ver lista de empleados (con salarios) — accesible para admin, editor y empleado (enfoque B)
    Route::resource('employees', EmployeeController::class)->only(['index', 'show']);

    // Crear, editar y eliminar empleados — SOLO admin
    // El editor NO tiene poder sobre empleados (solo ve la lista y gestiona tareas)
    Route::resource('employees', EmployeeController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('role:admin');

    // Tareas
    Route::resource('tasks', TaskController::class)->except(['index', 'show']);
    Route::post('/tasks/{task}/move', [TaskController::class, 'move'])->name('tasks.move');

    // Tablero Kanban
    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban.index');

    // Perfil de usuario (de Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
