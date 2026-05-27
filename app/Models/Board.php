<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Board (Tablero Kanban)
 *
 * Un tablero contiene columnas, y las columnas contienen tareas.
 */
class Board extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * Relación: Un tablero tiene MUCHAS columnas
     * orderBy('position') → las columnas se ordenan por su posición
     */
    public function columns()
    {
        return $this->hasMany(Column::class)->orderBy('position');
    }

    /**
     * Relación anidada: todas las tareas de un tablero (a través de sus columnas)
     * Uso: $board->tasks  →  devuelve todas las tareas del tablero
     */
    public function tasks()
    {
        return $this->hasManyThrough(Task::class, Column::class);
    }
}
