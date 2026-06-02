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
        if (!auth()->user()->canCreateTasks()) {
            abort(403, 'No tienes permiso para crear tareas.');
        }

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
        if (!auth()->user()->canCreateTasks()) {
            abort(403, 'No tienes permiso para crear tareas.');
        }

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
        if (!auth()->user()->canCreateTasks()) {
            abort(403, 'No tienes permiso para editar tareas.');
        }

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
        if (!auth()->user()->canCreateTasks()) {
            abort(403, 'No tienes permiso para editar tareas.');
        }

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
        if (!auth()->user()->canCreateTasks()) {
            abort(403, 'No tienes permiso para eliminar tareas.');
        }

        $task->delete();
        return redirect()->route('kanban.index')
                         ->with('success', 'Tarea eliminada.');
    }

    /**
     * Mueve una tarea entre columnas (AJAX desde el Kanban).
     */
    public function move(Request $request, Task $task)
    {
        $user = auth()->user();

        if ($user->isEmpleado() && $task->employee_id !== $user->employee_id) {
            return response()->json(['success' => false, 'message' => 'No puedes mover tareas de otros.'], 403);
        }

        $column = Column::find($request->column_id);

        if (!$column) {
            return response()->json(['success' => false, 'message' => 'Columna no válida.'], 422);
        }

        // Regla: Empleado puede completar su tarea, pero no puede sacarla de "Completado"
        if ($user->isEmpleado()) {
            $currentColumn = $task->column;

            if ($currentColumn && $currentColumn->name === 'Completado' && $column->name !== 'Completado') {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes regresar una tarea que ya está completada. Contacta a un Editor o Admin.'
                ], 403);
            }
        }

        $request->validate([
            'column_id' => 'required|exists:columns,id',
            'position'  => 'required|integer|min:0',
        ]);

        $task->update([
            'column_id' => $request->column_id,
            'position'  => $request->position,
        ]);

        return response()->json(['success' => true, 'task' => $task]);
    }
}
