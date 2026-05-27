<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Column (Columna del Kanban)
 *
 * Representa una etapa del flujo: Pendiente, En Progreso, Completado.
 * Pertenece a un Board y contiene múltiples Tasks.
 */
class Column extends Model
{
    use HasFactory;

    protected $fillable = ['board_id', 'name', 'position', 'color'];

    /**
     * Relación: Una columna PERTENECE a un tablero (many-to-one)
     * Uso: $column->board  →  devuelve el tablero al que pertenece
     */
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    /**
     * Relación: Una columna tiene MUCHAS tareas
     * orderBy('position') → las tareas se muestran en orden
     */
    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('position');
    }
}
