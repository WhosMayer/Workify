<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Column;
use App\Models\Employee;
use App\Models\Board;
use Illuminate\Http\Request;

/**
 * TaskController
 *
 * Gestiona tareas y el movimiento de tarjetas en el tablero Kanban.
 */
class TaskController extends Controller
{
    /**
     * Muestra el formulario para crear una nueva tarea.
     * Ruta: GET /tasks/create
     */
    public function create()
    {
        $employees = Employee::active()->orderBy('name')->get();
        $columns   = Column::with('board')->orderBy('position')->get();
        return view('tasks.create', compact('employees', 'columns'));
    }

    /**
     * Guarda la nueva tarea en la base de datos.
     * Ruta: POST /tasks
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'column_id'   => 'required|exists:columns,id',
            'employee_id' => 'required|exists:employees,id',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
        ]);

        // Calcula la posición: la tarea nueva va al final de la columna
        $validated['position'] = Task::where('column_id', $validated['column_id'])->count();

        Task::create($validated);

        return redirect()->route('kanban.index')
                         ->with('success', 'Tarea creada exitosamente.');
    }

    /**
     * Muestra el formulario de edición de una tarea.
     * Ruta: GET /tasks/{task}/edit
     */
    public function edit(Task $task)
    {
        $employees = Employee::active()->orderBy('name')->get();
        $columns   = Column::with('board')->orderBy('position')->get();
        return view('tasks.edit', compact('task', 'employees', 'columns'));
    }

    /**
     * Actualiza la tarea.
     * Ruta: PUT /tasks/{task}
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'column_id'   => 'required|exists:columns,id',
            'employee_id' => 'required|exists:employees,id',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
        ]);

        $task->update($validated);

        return redirect()->route('kanban.index')
                         ->with('success', 'Tarea actualizada exitosamente.');
    }

    /**
     * Elimina la tarea.
     * Ruta: DELETE /tasks/{task}
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('kanban.index')
                         ->with('success', 'Tarea eliminada.');
    }

    /**
     * Mueve una tarea a otra columna (llamado via AJAX desde el Kanban).
     * Ruta: POST /tasks/{task}/move
     *
     * El JavaScript del frontend envía: { column_id: X, position: Y }
     * Este método actualiza la columna y posición de la tarea.
     */
    public function move(Request $request, Task $task)
    {
        $request->validate([
            'column_id' => 'required|exists:columns,id',
            'position'  => 'required|integer|min:0',
        ]);

        $task->update([
            'column_id' => $request->column_id,
            'position'  => $request->position,
        ]);

        // response()->json() → devuelve JSON al frontend (para AJAX)
        return response()->json(['success' => true, 'task' => $task]);
    }
}
