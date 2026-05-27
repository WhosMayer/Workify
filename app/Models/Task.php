<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Task (Tarea)
 *
 * Una tarea vive dentro de una columna Kanban y está asignada a un empleado.
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'column_id',
        'employee_id',
        'priority',
        'due_date',
        'position',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Relación: Una tarea PERTENECE a una columna
     */
    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    /**
     * Relación: Una tarea PERTENECE a un empleado
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Acceso directo al tablero de la tarea (a través de su columna)
     * Uso: $task->board  →  devuelve el Board
     */
    public function getBoardAttribute()
    {
        return $this->column->board;
    }

    /**
     * Colores para las etiquetas de prioridad (usado en las vistas)
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'high'   => 'red',
            'medium' => 'yellow',
            'low'    => 'green',
            default  => 'gray',
        };
    }
}
