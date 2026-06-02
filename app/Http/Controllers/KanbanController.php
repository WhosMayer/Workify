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

        return view('kanban.index', compact('board'));
    }
}
