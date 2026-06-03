@extends('layouts.app')
@section('title', 'Empleados')
@section('page-title', 'Directorio de Empleados')
@section('page-subtitle', 'Administre y supervise su fuerza laboral.')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div></div>
    @if(auth()->user()?->isAdmin())
    <a href="{{ route('employees.create') }}"
       class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-bold hover:brightness-110 transition-all shadow-lg shadow-primary/20">
        <span class="material-symbols-outlined text-xl">person_add</span>
        Agregar Empleado
    </a>
    @endif
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="min-w-full">
        <thead>
            <tr class="border-b border-slate-100">
                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Nombre del Empleado</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Cargo</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Departamento</th>
                @if(auth()->user()?->isAdmin())
                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Salario</th>
                @endif
                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Tareas</th>
                <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($employees as $employee)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($employee->name, 0, 1)) }}{{ strtoupper(substr(strrchr($employee->name, ' ') ?: ' ', 1, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">{{ $employee->name }}</p>
                            <p class="text-xs text-slate-400">{{ $employee->user?->email ?? $employee->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">{{ $employee->position }}</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 text-xs font-semibold bg-primary/10 text-primary rounded-full">
                        {{ $employee->department }}
                    </span>
                </td>
                @if(auth()->user()?->isAdmin())
                <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                    {{ $employee->salary ? '$' . number_format($employee->salary, 2) : '-' }}
                </td>
                @endif
                <td class="px-6 py-4">
                    @if($employee->status === 'active')
                        <span class="px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full">● Activo</span>
                    @else
                        <span class="px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-500 rounded-full">● Inactivo</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">
                    {{ $employee->tasks()->count() }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('employees.show', $employee) }}"
                           class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-all" title="Ver">
                            <span class="material-symbols-outlined text-xl">visibility</span>
                        </a>
                        @if(auth()->user()?->isAdmin())
                        <a href="{{ route('employees.edit', $employee) }}"
                           class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Editar">
                            <span class="material-symbols-outlined text-xl">edit</span>
                        </a>
                        <form action="{{ route('employees.destroy', $employee) }}" method="POST"
                              onsubmit="return confirm('¿Seguro que deseas eliminar a {{ $employee->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Eliminar">
                                <span class="material-symbols-outlined text-xl">delete</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-16 text-center text-slate-400">
                    <span class="material-symbols-outlined text-5xl block mb-2">group_off</span>
                    No hay empleados registrados.
                    <a href="{{ route('employees.create') }}" class="text-primary hover:underline ml-1 font-semibold">Crear el primero</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($employees->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
        <span>Mostrando {{ $employees->firstItem() }} a {{ $employees->lastItem() }} de {{ $employees->total() }} miembros</span>
        {{ $employees->links() }}
    </div>
    @endif
</div>
@endsection