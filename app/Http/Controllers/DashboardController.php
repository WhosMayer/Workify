<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Task;
use App\Models\Column;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isEmpleado = $user && $user->isEmpleado() && $user->employee_id;

        $totalEmployees  = Employee::count();
        $pendingColumn   = Column::where('name', 'Pendiente')->first();
        $progressColumn  = Column::where('name', 'En Progreso')->first();
        $doneColumn      = Column::where('name', 'Completado')->first();

        if ($isEmpleado) {
            $tasksPending    = $pendingColumn  ? Task::where('column_id', $pendingColumn->id)->where('employee_id', $user->employee_id)->count()  : 0;
            $tasksInProgress = $progressColumn ? Task::where('column_id', $progressColumn->id)->where('employee_id', $user->employee_id)->count() : 0;
            $tasksCompleted  = $doneColumn     ? Task::where('column_id', $doneColumn->id)->where('employee_id', $user->employee_id)->count()     : 0;

            $recentTasks = Task::with(['employee', 'column'])
                ->where('employee_id', $user->employee_id)
                ->latest()
                ->take(4)
                ->get();
        } else {
            $tasksPending    = $pendingColumn  ? Task::where('column_id', $pendingColumn->id)->count()  : 0;
            $tasksInProgress = $progressColumn ? Task::where('column_id', $progressColumn->id)->count() : 0;
            $tasksCompleted  = $doneColumn     ? Task::where('column_id', $doneColumn->id)->count()     : 0;

            $recentTasks = Task::with(['employee', 'column'])->latest()->take(4)->get();
        }

        $weeklyData = [];
        $monthlyData = [];
        $maxWeekly = 1;
        $maxMonthly = 1;

        if (!$isEmpleado) {
            $days = ['LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB', 'DOM'];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $count = Task::whereDate('created_at', $date)->count();
                $weeklyData[] = [
                    'day'   => $days[$date->dayOfWeek === 0 ? 6 : $date->dayOfWeek - 1],
                    'count' => $count,
                ];
            }

            $months = ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $count = Task::whereYear('created_at', $date->year)
                             ->whereMonth('created_at', $date->month)
                             ->count();
                $monthlyData[] = [
                    'day'   => $months[$date->month - 1],
                    'count' => $count,
                ];
            }

            $maxWeekly  = max(array_column($weeklyData,  'count') ?: [1]);
            $maxMonthly = max(array_column($monthlyData, 'count') ?: [1]);
        }

        return view('dashboard', compact(
            'totalEmployees', 'tasksPending', 'tasksInProgress',
            'tasksCompleted', 'recentTasks',
            'weeklyData', 'monthlyData', 'maxWeekly', 'maxMonthly',
            'isEmpleado'
        ));
    }
}
