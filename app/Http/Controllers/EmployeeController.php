<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')->orderBy('name')->paginate(10);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        if (!auth()->user()->canManageEmployees()) {
            abort(403, 'No tienes permiso para crear empleados.');
        }

        return view('employees.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->canManageEmployees()) {
            abort(403, 'No tienes permiso para crear empleados.');
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:employees,email',
            'position'   => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'salary'     => 'nullable|numeric|min:0',
            'hire_date'  => 'required|date',
            'status'     => 'required|in:active,inactive',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')
                         ->with('success', 'Empleado creado exitosamente.');
    }

    public function show(Employee $employee)
    {
        $employee->load('tasks.column', 'user');
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        if (!auth()->user()->canManageEmployees()) {
            abort(403, 'No tienes permiso para editar empleados.');
        }

        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        if (!auth()->user()->canManageEmployees()) {
            abort(403, 'No tienes permiso para editar empleados.');
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:employees,email,' . $employee->id,
            'position'   => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'salary'     => 'nullable|numeric|min:0',
            'hire_date'  => 'required|date',
            'status'     => 'required|in:active,inactive',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')
                         ->with('success', 'Empleado actualizado exitosamente.');
    }

    public function destroy(Employee $employee)
    {
        if (!auth()->user()->canManageEmployees()) {
            abort(403, 'No tienes permiso para eliminar empleados.');
        }

        $employee->delete();
        return redirect()->route('employees.index')
                         ->with('success', 'Empleado eliminado exitosamente.');
    }
}