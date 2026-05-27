<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\Request;

/**
 * KanbanController
 *
 * Muestra el tablero Kanban con todas sus columnas y tareas.
 */
class KanbanController extends Controller
{
    /**
     * Muestra el tablero Kanban principal.
     * Ruta: GET /kanban
     *
     * Usa eager loading (with) para cargar todo en una sola consulta:
     * Board → columns → tasks → employee
     * Sin esto, Laravel haría N+1 consultas (muy lento).
     */
    public function index()
    {
        $user = auth()->user();

        // Si es empleado, solo verá las tareas asignadas a su empleado vinculado
        $employeeIdFilter = null;
        if ($user && $user->isEmpleado() && $user->employee_id) {
            $employeeIdFilter = $user->employee_id;
        }

        // Carga el tablero con columnas y tareas
        $board = Board::with([
            'columns' => function ($query) {
                $query->orderBy('position');
            },
            'columns.tasks' => function ($query) use ($employeeIdFilter) {
                $query->orderBy('position')->with('employee');

                // Filtro clave para rol "empleado": solo sus tareas
                if ($employeeIdFilter) {
                    $query->where('employee_id', $employeeIdFilter);
                }
            }
        ])->first();

        // Si no hay tablero, créalo con las 3 columnas por defecto
        if (!$board) {
            $board = Board::create([
                'name'        => 'Tablero Principal',
                'description' => 'Tablero de gestión de tareas',
            ]);

            $columns = [
                ['name' => 'Pendiente',    'position' => 0, 'color' => '#6366f1'],
                ['name' => 'En Progreso',  'position' => 1, 'color' => '#f59e0b'],
                ['name' => 'Completado',   'position' => 2, 'color' => '#10b981'],
            ];

            foreach ($columns as $col) {
                $board->columns()->create($col);
            }

            // Recarga aplicando el mismo filtro
            $board->load([
                'columns.tasks' => function ($query) use ($employeeIdFilter) {
                    $query->orderBy('position')->with('employee');
                    if ($employeeIdFilter) {
                        $query->where('employee_id', $employeeIdFilter);
                    }
                }
            ]);
        }

        return view('kanban.index', compact('board'));
    }
}
