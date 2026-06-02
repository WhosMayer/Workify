@extends('layouts.app')

@section('title', 'Panel')
@section('page-title', 'Vista General del Panel')
@section('page-subtitle', 'Bienvenido de nuevo, esto es lo que está pasando hoy.')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    @if(!($isEmpleado ?? false))
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-slate-500 font-medium">Total Empleados</span>
            <div class="p-2 rounded-lg" style="background-color:#e0f7ff;color:#24aceb;">
                <span class="material-symbols-outlined">groups</span>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h3 class="text-3xl font-black">{{ $totalEmployees }}</h3>
            <span class="text-emerald-500 text-sm font-bold">Activos</span>
        </div>
        <p class="text-xs text-slate-400 mt-2">Personal registrado en el sistema</p>
    </div>
    @endif

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-slate-500 font-medium">{{ ($isEmpleado ?? false) ? 'Mis Tareas Pendientes' : 'Tareas Pendientes' }}</span>
            <div class="p-2 rounded-lg" style="background-color:#fff7e6;color:#f59e0b;">
                <span class="material-symbols-outlined">pending_actions</span>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h3 class="text-3xl font-black">{{ $tasksPending }}</h3>
            <span class="text-sm font-bold" style="color:#f59e0b;">Por hacer</span>
        </div>
        <p class="text-xs text-slate-400 mt-2">{{ ($isEmpleado ?? false) ? 'Tus tareas que requieren atención' : 'Requieren atención inmediata' }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-slate-500 font-medium">{{ ($isEmpleado ?? false) ? 'Mis Tareas En Progreso' : 'En Progreso' }}</span>
            <div class="p-2 rounded-lg" style="background-color:#e0f7ff;color:#24aceb;">
                <span class="material-symbols-outlined">sync_alt</span>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h3 class="text-3xl font-black">{{ $tasksInProgress }}</h3>
            <span class="text-sm font-bold" style="color:#24aceb;">Activas</span>
        </div>
        <p class="text-xs text-slate-400 mt-2">{{ ($isEmpleado ?? false) ? 'Tus tareas que estás gestionando' : 'Siendo gestionadas ahora' }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-slate-500 font-medium">{{ ($isEmpleado ?? false) ? 'Mis Tareas Completadas' : 'Completadas' }}</span>
            <div class="p-2 rounded-lg" style="background-color:#e6faf3;color:#10b981;">
                <span class="material-symbols-outlined">task_alt</span>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h3 class="text-3xl font-black">{{ $tasksCompleted }}</h3>
            <span class="text-emerald-500 text-sm font-bold">Este mes</span>
        </div>
        <p class="text-xs text-slate-400 mt-2">{{ ($isEmpleado ?? false) ? 'Tus tareas finalizadas' : 'Finalizadas este mes' }}</p>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

    {{-- Distribución de Tareas - Gráfico de Pastel --}}
    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
        <h3 class="text-lg font-bold mb-6">Distribución de Tareas</h3>
        
        <div class="flex flex-col md:flex-row items-center gap-8">
            <!-- Gráfico de Pastel -->
            <div class="flex-shrink-0">
                <canvas id="tasksPieChart" width="180" height="180"></canvas>
            </div>

            <!-- Leyenda -->
            <div class="space-y-3 text-sm">
                @php
                    $total = max($tasksPending + $tasksInProgress + $tasksCompleted, 1);
                    
                    // Cálculo de porcentajes con redondeo inteligente (siempre suman 100%)
                    $rawPercentages = [
                        'pending'    => ($tasksPending / $total) * 100,
                        'inProgress' => ($tasksInProgress / $total) * 100,
                        'completed'  => ($tasksCompleted / $total) * 100,
                    ];
                    
                    // Redondeamos normalmente
                    $pendingPercent    = round($rawPercentages['pending']);
                    $inProgressPercent = round($rawPercentages['inProgress']);
                    $completedPercent  = round($rawPercentages['completed']);
                    
                    // Ajustamos para que siempre sumen exactamente 100%
                    $sum = $pendingPercent + $inProgressPercent + $completedPercent;
                    $diff = 100 - $sum;
                    
                    if ($diff !== 0) {
                        // Encontramos el más grande y le sumamos/restamos la diferencia
                        $max = max($pendingPercent, $inProgressPercent, $completedPercent);
                        if ($pendingPercent === $max) {
                            $pendingPercent += $diff;
                        } elseif ($inProgressPercent === $max) {
                            $inProgressPercent += $diff;
                        } else {
                            $completedPercent += $diff;
                        }
                    }
                @endphp

                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full" style="background-color:#f59e0b;"></span>
                    <span class="font-medium text-slate-600">Pendiente</span>
                    <span class="ml-auto font-bold" style="color:#f59e0b;">{{ $tasksPending }} ({{ $pendingPercent }}%)</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full" style="background-color:#24aceb;"></span>
                    <span class="font-medium text-slate-600">En Progreso</span>
                    <span class="ml-auto font-bold" style="color:#24aceb;">{{ $tasksInProgress }} ({{ $inProgressPercent }}%)</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full" style="background-color:#10b981;"></span>
                    <span class="font-medium text-slate-600">Completadas</span>
                    <span class="ml-auto font-bold" style="color:#10b981;">{{ $tasksCompleted }} ({{ $completedPercent }}%)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js para el gráfico de pastel -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('tasksPieChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pendiente', 'En Progreso', 'Completadas'],
                datasets: [{
                    data: [{{ $tasksPending }}, {{ $tasksInProgress }}, {{ $tasksCompleted }}],
                    backgroundColor: ['#f59e0b', '#24aceb', '#10b981'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                cutout: '65%', // Hace que sea un donut (círculo con agujero)
                plugins: {
                    legend: {
                        display: false // Usamos nuestra leyenda personalizada
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                // Usamos los porcentajes ya ajustados para que siempre sumen 100%
                                let percentage;
                                if (context.dataIndex === 0) percentage = {{ $pendingPercent }};
                                else if (context.dataIndex === 1) percentage = {{ $inProgressPercent }};
                                else percentage = {{ $completedPercent }};
                                
                                return context.label + ': ' + context.raw + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>

    {{-- Tareas recientes --}}
    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold">{{ ($isEmpleado ?? false) ? 'Mis Tareas Recientes' : 'Tareas Recientes' }}</h3>
            <a href="{{ route('kanban.index') }}" class="text-sm text-primary font-semibold hover:underline">Ver tareas →</a>
        </div>

        @forelse($recentTasks as $task)
            <div class="flex items-center gap-4 py-3 border-b border-slate-100 last:border-0">
                <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($task->employee->name ?? 'N', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm text-slate-800 truncate">{{ $task->title }}</p>
                    <p class="text-xs text-slate-500">{{ $task->employee->name ?? 'Sin asignar' }} · {{ $task->column->name ?? '' }}</p>
                </div>
                <div>
                    @php
                        $pc = ['high' => 'bg-red-100 text-red-700', 'medium' => 'bg-amber-100 text-amber-700', 'low' => 'bg-emerald-100 text-emerald-700'];
                        $pcolor = $pc[$task->priority] ?? 'bg-slate-100 text-slate-600';
                    @endphp
                    <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold {{ $pcolor }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-slate-400 text-sm">
                No hay tareas recientes.
            </div>
        @endforelse
    </div>

</div>

@endsection
