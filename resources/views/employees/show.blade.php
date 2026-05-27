@extends('layouts.app')
@section('title', $employee->name)
@section('page-title', $employee->name)
@section('page-subtitle', $employee->position . ' · ' . $employee->department)

@section('content')
<a href="{{ route('employees.index') }}"
   class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-primary mb-6 transition-colors">
    <span class="material-symbols-outlined text-lg">arrow_back</span> Volver a empleados
</a>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Perfil --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 text-center">
        <div class="w-20 h-20 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-bold text-2xl mx-auto mb-4">
            {{ strtoupper(substr($employee->name, 0, 1)) }}{{ strtoupper(substr(strrchr($employee->name, ' ') ?: ' ', 1, 1)) }}
        </div>
        <h2 class="text-xl font-bold text-slate-800">{{ $employee->name }}</h2>
        <p class="text-primary font-semibold text-sm mt-1">{{ $employee->position }}</p>
        <p class="text-slate-400 text-sm">{{ $employee->department }}</p>

        <div class="mt-3">
            @if($employee->status === 'active')
                <span class="px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full">● Activo</span>
            @else
                <span class="px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-500 rounded-full">● Inactivo</span>
            @endif
        </div>

        <div class="mt-6 text-sm text-slate-600 space-y-2 text-left border-t border-slate-100 pt-4">
            <p class="flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400 text-lg">mail</span>
                {{ $employee->email }}
            </p>
            @if($employee->phone)
            <p class="flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400 text-lg">phone</span>
                {{ $employee->phone }}
            </p>
            @endif
            @if($employee->salary)
            <p class="flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400 text-lg">payments</span>
                ${{ number_format($employee->salary, 2) }}
            </p>
            @endif
            <p class="flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400 text-lg">calendar_today</span>
                Desde {{ $employee->hire_date->format('d/m/Y') }}
            </p>
        </div>

        <a href="{{ route('employees.edit', $employee) }}"
           class="mt-6 w-full flex items-center justify-center gap-2 bg-primary text-white py-2.5 rounded-xl font-bold hover:brightness-110 transition-all">
            <span class="material-symbols-outlined text-xl">edit</span> Editar
        </a>
    </div>

    {{-- Tareas --}}
    <div class="md:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">task_alt</span>
            Tareas asignadas ({{ $employee->tasks->count() }})
        </h3>

        @forelse($employee->tasks as $task)
        <div class="border border-slate-100 rounded-xl p-4 mb-3 hover:border-primary/30 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-semibold text-slate-800">{{ $task->title }}</p>
                    @if($task->description)
                    <p class="text-sm text-slate-400 mt-1">{{ Str::limit($task->description, 80) }}</p>
                    @endif
                </div>
                <div class="flex gap-2 ml-4 flex-shrink-0">
                    @php $colors = ['high' => 'red', 'medium' => 'amber', 'low' => 'emerald']; $c = $colors[$task->priority] ?? 'slate'; @endphp
                    <span class="px-2 py-1 text-xs font-semibold bg-{{ $c }}-100 text-{{ $c }}-700 rounded-full">
                        {{ ucfirst($task->priority) }}
                    </span>
                    <span class="px-2 py-1 text-xs font-semibold bg-primary/10 text-primary rounded-full">
                        {{ $task->column->name }}
                    </span>
                </div>
            </div>
            @if($task->due_date)
            <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">calendar_today</span>
                Vence: {{ $task->due_date->format('d/m/Y') }}
            </p>
            @endif
        </div>
        @empty
        <div class="text-center py-12 text-slate-400">
            <span class="material-symbols-outlined text-5xl block mb-2">assignment_turned_in</span>
            Este empleado no tiene tareas asignadas.
        </div>
        @endforelse
    </div>
</div>
@endsection