@extends('layouts.app')

@section('title', 'Panel')
@section('page-title', 'Vista General del Panel')
@section('page-subtitle', 'Bienvenido de nuevo, esto es lo que está pasando hoy.')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

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

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-slate-500 font-medium">Tareas Pendientes</span>
            <div class="p-2 rounded-lg" style="background-color:#fff7e6;color:#f59e0b;">
                <span class="material-symbols-outlined">pending_actions</span>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h3 class="text-3xl font-black">{{ $tasksPending }}</h3>
            <span class="text-sm font-bold" style="color:#f59e0b;">Por hacer</span>
        </div>
        <p class="text-xs text-slate-400 mt-2">Requieren atención inmediata</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-slate-500 font-medium">En Progreso</span>
            <div class="p-2 rounded-lg" style="background-color:#e0f7ff;color:#24aceb;">
                <span class="material-symbols-outlined">sync_alt</span>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h3 class="text-3xl font-black">{{ $tasksInProgress }}</h3>
            <span class="text-sm font-bold" style="color:#24aceb;">Activas</span>
        </div>
        <p class="text-xs text-slate-400 mt-2">Siendo gestionadas ahora</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-slate-500 font-medium">Completadas</span>
            <div class="p-2 rounded-lg" style="background-color:#e6faf3;color:#10b981;">
                <span class="material-symbols-outlined">task_alt</span>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h3 class="text-3xl font-black">{{ $tasksCompleted }}</h3>
            <span class="text-emerald-500 text-sm font-bold">Este mes</span>
        </div>
        <p class="text-xs text-slate-400 mt-2">Finalizadas este mes</p>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

    {{-- Resumen por columna --}}
    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
        <h3 class="text-lg font-bold mb-6">Distribución de Tareas</h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-slate-600">Pendiente</span>
                    <span class="font-bold" style="color:#f59e0b;">{{ $tasksPending }}</span>
                </div>
                <div style="width:100%; background-color:#f1f5f9; border-radius:999px; height:10px;">
                    @php $total = max($tasksPending + $tasksInProgress + $tasksCompleted, 1); @endphp
                    <div style="width:{{ ($tasksPending / $total) * 100 }}%; background-color:#f59e0b; border-radius:999px; height:10px;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-slate-600">En Progreso</span>
                    <span class="font-bold" style="color:#24aceb;">{{ $tasksInProgress }}</span>
                </div>
                <div style="width:100%; background-color:#f1f5f9; border-radius:999px; height:10px;">
                    <div style="width:{{ ($tasksInProgress / $total) * 100 }}%; background-color:#24aceb; border-radius:999px; height:10px;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-slate-600">Completadas</span>
                    <span class="font-bold" style="color:#10b981;">{{ $tasksCompleted }}</span>
                </div>
                <div style="width:100%; background-color:#f1f5f9; border-radius:999px; height:10px;">
                    <div style="width:{{ ($tasksCompleted / $total) * 100 }}%; background-color:#10b981; border-radius:999px; height:10px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tareas recientes --}}
    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold">Tareas Recientes</h3>
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
