@extends('layouts.app')
@section('title', isset($employee) ? 'Editar Empleado' : 'Nuevo Empleado')
@section('page-title', isset($employee) ? 'Editar Empleado' : 'Agregar Empleado')
@section('page-subtitle', isset($employee) ? 'Modifica la información del empleado.' : 'Completa el formulario para registrar un nuevo empleado.')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('employees.index') }}"
       class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-primary mb-6 transition-colors">
        <span class="material-symbols-outlined text-lg">arrow_back</span> Volver a empleados
    </a>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
        <form action="{{ isset($employee) ? route('employees.update', $employee) : route('employees.store') }}"
              method="POST" class="space-y-5">
            @csrf
            @if(isset($employee)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nombre completo *</label>
                    <input type="text" name="name" value="{{ old('name', $employee->name ?? '') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 @error('name') border-red-400 @enderror"
                           placeholder="Juan Pérez">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $employee->email ?? '') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 @error('email') border-red-400 @enderror"
                           placeholder="juan@empresa.com">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone', $employee->phone ?? '') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50"
                           placeholder="+57 300 123 4567">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Salario</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-slate-400 text-sm font-bold">$</span>
                        <input type="number" name="salary" step="0.01" min="0"
                               value="{{ old('salary', $employee->salary ?? '') }}"
                               class="w-full pl-8 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/50"
                               placeholder="0.00">
                    </div>
                    @error('salary') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Cargo *</label>
                    <input type="text" name="position" value="{{ old('position', $employee->position ?? '') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 @error('position') border-red-400 @enderror"
                           placeholder="Desarrollador Web">
                    @error('position') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Departamento *</label>
                    <input type="text" name="department" value="{{ old('department', $employee->department ?? '') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 @error('department') border-red-400 @enderror"
                           placeholder="Tecnología">
                    @error('department') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Fecha de contratación *</label>
                    <input type="date" name="hire_date"
                           value="{{ old('hire_date', isset($employee) ? $employee->hire_date->format('Y-m-d') : '') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 @error('hire_date') border-red-400 @enderror">
                    @error('hire_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Estado *</label>
                    <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <option value="active"   {{ old('status', $employee->status ?? 'active') === 'active'   ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ old('status', $employee->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-100">
                <button type="submit"
                        class="bg-primary text-white px-6 py-2.5 rounded-xl font-bold hover:brightness-110 transition-all shadow-lg shadow-primary/20">
                    {{ isset($employee) ? 'Actualizar Empleado' : 'Guardar Empleado' }}
                </button>
                <a href="{{ route('employees.index') }}"
                   class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-medium">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection