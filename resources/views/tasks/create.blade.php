@extends('layouts.app')
@section('title', isset($task) ? 'Editar Tarea' : 'Nueva Tarea')
@section('page-title', isset($task) ? 'Editar Tarea' : 'Nueva Tarea')
@section('page-subtitle', 'Completa los detalles de la tarea.')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('kanban.index') }}"
       class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-primary mb-6 transition-colors">
        <span class="material-symbols-outlined text-lg">arrow_back</span> Volver al tablero
    </a>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">edit_note</span>
            Detalles de la Tarea
        </h3>

        <form action="{{ isset($task) ? route('tasks.update', $task) : route('tasks.store') }}"
              method="POST" class="space-y-5">
            @csrf
            @if(isset($task)) @method('PUT') @endif

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nombre de la Tarea *</label>
                <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 @error('title') border-red-400 @enderror"
                       placeholder="ej. Auditoría del sistema de diseño">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Descripción</label>
                <textarea name="description" rows="3"
                          class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50"
                          placeholder="Describa brevemente los objetivos y entregables...">{{ old('description', $task->description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Empleado Asignado *</label>
                    <select name="employee_id"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 @error('employee_id') border-red-400 @enderror">
                        <option value="">Seleccionar un empleado</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                    {{ old('employee_id', $task->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Fecha de Entrega</label>
                    <input type="date" name="due_date"
                           value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Columna / Estado *</label>
                    <select name="column_id"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 @error('column_id') border-red-400 @enderror">
                        <option value="">Seleccionar estado</option>
                        @foreach($columns as $column)
                            <option value="{{ $column->id }}"
                                    {{ old('column_id', $task->column_id ?? '') == $column->id ? 'selected' : '' }}>
                                {{ $column->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('column_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Prioridad *</label>
                    <select name="priority"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <option value="low"    {{ old('priority', $task->priority ?? '') === 'low'    ? 'selected' : '' }}>🟢 Baja</option>
                        <option value="medium" {{ old('priority', $task->priority ?? 'medium') === 'medium' ? 'selected' : '' }}>🟡 Media</option>
                        <option value="high"   {{ old('priority', $task->priority ?? '') === 'high'   ? 'selected' : '' }}>🔴 Alta</option>
                    </select>
                </div>

            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-100">
                <button type="submit"
                        class="bg-primary text-white px-6 py-2.5 rounded-xl font-bold hover:brightness-110 transition-all shadow-lg shadow-primary/20">
                    {{ isset($task) ? 'Actualizar Tarea' : 'Guardar Tarea' }}
                </button>
                <a href="{{ route('kanban.index') }}"
                   class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-medium">
                    Cancelar
                </a>
            </div>
        </form>

        <div class="mt-6 p-4 bg-primary/5 border border-primary/10 rounded-xl flex items-start gap-3">
            <span class="material-symbols-outlined text-primary text-xl mt-0.5">info</span>
            <div>
                <p class="text-sm font-semibold text-primary">Consejo Profesional</p>
                <p class="text-xs text-slate-500 mt-0.5">Los empleados asignados podrán ver esta tarea en el tablero Kanban inmediatamente.</p>
            </div>
        </div>
    </div>
</div>
@endsection
