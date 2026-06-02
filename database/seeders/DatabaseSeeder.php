<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder
 *
 * Inserta datos de prueba en la base de datos.
 * Ejecutar con: php artisan db:seed
 * O junto con las migraciones: php artisan migrate --seed
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. EMPLEADOS ──────────────────────────────────────────
        $employees = [
            ['name' => 'Mayer Gómez',     'email' => 'mayer@empresa.com',  'position' => 'Líder Técnico',          'department' => 'Tecnología',  'phone' => '+57 300 555 7788', 'hire_date' => '2019-02-10', 'status' => 'active'],
            ['name' => 'Milton Muñoz',    'email' => 'milton@empresa.com', 'position' => 'Scrum Master',           'department' => 'Gerencia',    'phone' => '+57 301 999 3344', 'hire_date' => '2020-06-15', 'status' => 'active'],
            ['name' => 'Ana García',      'email' => 'ana@empresa.com',    'position' => 'Desarrolladora Frontend', 'department' => 'Tecnología',  'phone' => '+57 300 111 2233', 'hire_date' => '2022-03-15', 'status' => 'active'],
            ['name' => 'Carlos López',    'email' => 'carlos@empresa.com', 'position' => 'Desarrollador Backend',  'department' => 'Tecnología',  'phone' => '+57 301 444 5566', 'hire_date' => '2021-07-01', 'status' => 'active'],
            ['name' => 'María Torres',    'email' => 'maria@empresa.com',  'position' => 'Diseñadora UX/UI',       'department' => 'Diseño',       'phone' => '+57 302 777 8899', 'hire_date' => '2023-01-10', 'status' => 'active'],
            ['name' => 'Pedro Martínez',  'email' => 'pedro@empresa.com',  'position' => 'QA Engineer',            'department' => 'Tecnología',  'phone' => null,               'hire_date' => '2022-09-20', 'status' => 'active'],
            ['name' => 'Laura Sánchez',   'email' => 'laura@empresa.com',  'position' => 'Project Manager',        'department' => 'Gerencia',    'phone' => '+57 305 000 1122', 'hire_date' => '2020-05-12', 'status' => 'active'],
            ['name' => 'Andrés Ruiz',     'email' => 'andres@empresa.com', 'position' => 'DevOps Engineer',        'department' => 'Infraestructura', 'phone' => null,            'hire_date' => '2021-11-30', 'status' => 'inactive'],
        ];

        foreach ($employees as $data) {
            Employee::create($data);
        }

        // ── 2. TABLERO Y COLUMNAS ─────────────────────────────────
        $board = Board::create([
            'name'        => 'Tablero Principal',
            'description' => 'Gestión de tareas del equipo de desarrollo',
        ]);

        $pending    = $board->columns()->create(['name' => 'Pendiente',   'position' => 0, 'color' => '#6366f1']);
        $inProgress = $board->columns()->create(['name' => 'En Progreso', 'position' => 1, 'color' => '#f59e0b']);
        $done       = $board->columns()->create(['name' => 'Completado',  'position' => 2, 'color' => '#10b981']);

        // ── 3. TAREAS ─────────────────────────────────────────────
        // Recupera empleados por nombre para asignarles tareas
        $mayer   = Employee::where('name', 'Mayer Gómez')->first();
        $milton  = Employee::where('name', 'Milton Muñoz')->first();
        $ana     = Employee::where('name', 'Ana García')->first();
        $carlos  = Employee::where('name', 'Carlos López')->first();
        $maria   = Employee::where('name', 'María Torres')->first();
        $pedro   = Employee::where('name', 'Pedro Martínez')->first();
        $laura   = Employee::where('name', 'Laura Sánchez')->first();

        // Tareas en PENDIENTE
        $pending->tasks()->createMany([
            ['title' => 'Diseñar pantalla de login',          'description' => 'Crear mockup en Figma con los nuevos lineamientos de marca.',           'employee_id' => $maria->id,  'priority' => 'high',   'due_date' => now()->addDays(5),  'position' => 0],
            ['title' => 'Configurar entorno de staging',      'description' => 'Instalar y configurar el servidor de pruebas en AWS.',                  'employee_id' => $mayer->id,  'priority' => 'medium', 'due_date' => now()->addDays(7),  'position' => 1],
            ['title' => 'Documentar API REST',                'description' => 'Generar documentación con Swagger para todos los endpoints del módulo.', 'employee_id' => $ana->id,    'priority' => 'low',    'due_date' => now()->addDays(14), 'position' => 2],
            ['title' => 'Reunión de planificación Sprint 4',  'description' => null,                                                                     'employee_id' => $milton->id, 'priority' => 'high',   'due_date' => now()->addDays(2),  'position' => 3],
        ]);

        // Tareas EN PROGRESO
        $inProgress->tasks()->createMany([
            ['title' => 'Implementar módulo de reportes',     'description' => 'Generar PDFs con gráficas de ventas mensuales.',                        'employee_id' => $milton->id, 'priority' => 'high',   'due_date' => now()->addDays(3),  'position' => 0],
            ['title' => 'Refactorizar módulo de pagos',       'description' => 'Migrar de la librería antigua a Stripe v3.',                            'employee_id' => $ana->id,    'priority' => 'high',   'due_date' => now()->subDays(1),  'position' => 1],
            ['title' => 'Pruebas de regresión v2.1',          'description' => 'Ejecutar suite completa de tests automatizados.',                       'employee_id' => $pedro->id,  'priority' => 'medium', 'due_date' => now()->addDays(4),  'position' => 2],
        ]);

        // Tareas COMPLETADAS
        $done->tasks()->createMany([
            ['title' => 'Setup inicial del proyecto Laravel', 'description' => 'Instalación, configuración de .env y conexión a la base de datos.',     'employee_id' => $mayer->id,  'priority' => 'high',   'due_date' => now()->subDays(10), 'position' => 0],
            ['title' => 'Diseño del sistema de base de datos','description' => 'Diagrama ER y definición de todas las tablas y relaciones.',             'employee_id' => $maria->id,  'priority' => 'high',   'due_date' => now()->subDays(8),  'position' => 1],
            ['title' => 'Módulo de gestión de empleados',     'description' => 'Implementar el CRUD completo para administrar empleados (crear, editar, eliminar y listar).', 'employee_id' => $ana->id, 'priority' => 'medium', 'due_date' => now()->subDays(5), 'position' => 2],
            ['title' => 'Autenticación con Laravel Breeze',   'description' => 'Login, registro y recuperación de contraseña.',                         'employee_id' => $milton->id, 'priority' => 'medium', 'due_date' => now()->subDays(3),  'position' => 3],
        ]);

        // ── 4. USUARIOS DE PRUEBA CON ROLES ─────────────────────
        // Usuarios de acceso al sistema (vinculados a sus registros de empleado)

        // Admin
        \App\Models\User::updateOrCreate(
            ['email' => 'mayer@admin.com'],
            [
                'name'        => 'Mayer Gómez',
                'password'    => bcrypt('password'),
                'role'        => 'admin',
                'employee_id' => $mayer->id,
            ]
        );

        // Editor
        \App\Models\User::updateOrCreate(
            ['email' => 'milton@editor.com'],
            [
                'name'        => 'Milton Muñoz',
                'password'    => bcrypt('password'),
                'role'        => 'editor',
                'employee_id' => $milton->id,
            ]
        );

        // Empleado normal (vinculado a Ana García)
        \App\Models\User::updateOrCreate(
            ['email' => 'ana@empleado.com'],
            [
                'name'        => 'Ana García (Empleado)',
                'password'    => bcrypt('password'),
                'role'        => 'empleado',
                'employee_id' => $ana->id,
            ]
        );

        $this->command->info('✅ Datos de prueba creados exitosamente.');
        $this->command->info('   - 6 empleados');
        $this->command->info('   - 1 tablero con 3 columnas');
        $this->command->info('   - 11 tareas distribuidas (incluyendo tareas de Mayer y Milton)');
        $this->command->info('   - Usuarios de prueba (todos con password = "password"):');
        $this->command->info('       mayer@admin.com   → Mayer Gómez (admin)');
        $this->command->info('       milton@editor.com → Milton Muñoz (editor)');
        $this->command->info('       ana@empleado.com  → Ana García (empleado, solo ve sus propias tareas)');
    }
}
