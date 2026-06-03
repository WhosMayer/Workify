<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Listado y detalle de empleados (disponible para todos los roles)
    Route::resource('employees', EmployeeController::class)->only(['index', 'show']);

    // CRUD de empleados (solo administrador)
    Route::middleware('role:admin')->group(function () {
        Route::resource('employees', EmployeeController::class)
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    });

    // Crear, editar y eliminar tareas (solo admin y editor)
    Route::middleware('role:admin,editor')->group(function () {
        Route::resource('tasks', TaskController::class)->except(['index', 'show']);
    });

    Route::post('/tasks/{task}/move', [TaskController::class, 'move'])->name('tasks.move');

    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban.index');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
