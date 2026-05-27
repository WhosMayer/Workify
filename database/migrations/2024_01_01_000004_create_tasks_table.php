<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla 'tasks' (Tareas).
     * Cada tarea pertenece a una columna Kanban y tiene un empleado asignado.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');                           // Título de la tarea
            $table->text('description')->nullable();           // Descripción detallada
            $table->foreignId('column_id')                    // Columna donde está la tarea
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('employee_id')                  // Empleado responsable
                  ->constrained()
                  ->onDelete('cascade');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium'); // Prioridad
            $table->date('due_date')->nullable();              // Fecha límite
            $table->integer('position')->default(0);          // Posición dentro de la columna
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
