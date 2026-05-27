<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla 'employees' en la base de datos.
     * Campos: id, nombre, email, cargo, departamento, teléfono, fechas.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();                                          // ID autoincremental (clave primaria)
            $table->string('name');                               // Nombre completo
            $table->string('email')->unique();                    // Email único
            $table->string('position');                           // Cargo / Puesto
            $table->string('department');                         // Departamento
            $table->string('phone')->nullable();                  // Teléfono (opcional)
            $table->date('hire_date');                            // Fecha de contratación
            $table->enum('status', ['active', 'inactive'])->default('active'); // Estado
            $table->timestamps();                                 // created_at y updated_at
        });
    }

    /**
     * Elimina la tabla si se revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
