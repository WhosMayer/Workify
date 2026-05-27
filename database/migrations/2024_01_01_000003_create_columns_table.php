<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla 'columns' (Columnas del Kanban).
     * Cada columna pertenece a un tablero y representa una etapa:
     * Pendiente | En Progreso | Completado
     */
    public function up(): void
    {
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')              // Relación con el tablero
                  ->constrained()
                  ->onDelete('cascade');               // Si se borra el tablero, se borran las columnas
            $table->string('name');                    // Nombre: "Pendiente", "En Progreso", "Completado"
            $table->integer('position')->default(0);   // Orden de la columna (0, 1, 2...)
            $table->string('color')->default('#6366f1'); // Color de la columna
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('columns');
    }
};
