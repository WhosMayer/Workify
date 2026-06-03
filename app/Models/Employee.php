<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Employee (Empleado)
 *
 * Representa a un empleado de la organización.
 * Un empleado puede tener muchas tareas asignadas.
 */
class Employee extends Model
{
    use HasFactory;

    /**
     * $fillable: campos que se pueden asignar masivamente (ej: Employee::create([...]))
     * Sin esto, Laravel bloquea la asignación masiva por seguridad.
     */
    protected $fillable = [
        'name',
        'email',
        'position',
        'department',
        'phone',
        'salary',
        'hire_date',
        'status',
    ];

    /**
     * $casts: convierte automáticamente el campo al tipo indicado
     * 'hire_date' se convierte a objeto Carbon (fecha) al leerlo del BD
     */
    protected $casts = [
        'hire_date' => 'date',
        'salary'    => 'decimal:2',
    ];

    /**
     * Relación: Un empleado tiene MUCHAS tareas (one-to-many)
     * Uso: $employee->tasks  →  devuelve todas sus tareas
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Relación: Un empleado PUEDE tener un usuario de login vinculado (one-to-one inverse)
     * Uso: $employee->user  →  devuelve el User asociado (si existe)
     * Esto permite mostrar el email de login (ej: mayer@admin.com) en lugar del email interno del empleado (@empresa.com)
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Scope: filtrar solo empleados activos
     * Uso: Employee::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
