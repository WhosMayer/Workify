<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\Request;

class KanbanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $employeeIdFilter = null;
        if ($user && $user->isEmpleado() && $user->employee_id) {
            $employeeIdFilter = $user->employee_id;
        }

        $board = Board::with([
            'columns' => function ($query) {
                $query->orderBy('position');
            },
            'columns.tasks' => function ($query) use ($employeeIdFilter) {
                $query->orderBy('position')->with('employee');

                if ($employeeIdFilter) {
                    $query->where('employee_id', $employeeIdFilter);
                }
            }
        ])->first();

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

            $board->load([
                'columns.tasks' => function ($query) use ($employeeIdFilter) {
                    $query->orderBy('position')->with('employee');
                    if ($employeeIdFilter) {
                        $query->where('employee_id', $employeeIdFilter);
                    }
                }
            ]);
        }

        // Post-filter for robustness: ensure empleado users only ever see their own tasks
        // (or none if they have no linked employee record). This guarantees the rule even
        // if eager loading constraints behave unexpectedly in some edge cases.
        if ($user && $user->isEmpleado()) {
            $empId = $user->employee_id;
            foreach ($board->columns as $column) {
                $tasks = $column->tasks;
                if ($empId) {
                    $filtered = $tasks->where('employee_id', $empId);
                } else {
                    $filtered = collect();
                }
                $column->setRelation('tasks', $filtered->values());
            }
        }

        return view('kanban.index', compact('board'));
    }
}
