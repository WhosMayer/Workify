# Workify

**Sistema de Gestión de Empleados con Tablero Kanban**

Workify es una aplicación web desarrollada en Laravel 12 que permite la administración de personal y el control visual de tareas mediante un tablero Kanban interactivo, con un sistema de control de acceso basado en roles (RBAC) robusto.

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![SQLite](https://img.shields.io/badge/SQLite-3-lightgrey)

## Características Principales

- **Autenticación personalizada**: Páginas de login, registro y recuperación de contraseña con diseño propio (Workify branding).
- **Sistema de roles detallado**:
  - **Admin**: Control total (empleados y tareas).
  - **Editor**: Gestión de tareas y visualización de empleados (sin salarios).
  - **Empleado**: Solo ve y mueve sus propias tareas en el Kanban.
- **Kanban funcional** con drag & drop (SortableJS) y reglas de negocio:
  - Los empleados **no pueden** regresar tareas del estado "Completado".
- **Dashboard** con estadísticas, gráfico de pastel y barras de actividad.
- **Gestión de empleados** con CRUD completo (solo Admin).
- **Seeders** con datos de prueba listos para demostración.

## Roles y Permisos

| Funcionalidad              | Admin | Editor | Empleado |
|---------------------------|-------|--------|----------|
| Crear/Editar/Eliminar empleados | ✔     | ✘      | ✘        |
| Ver salarios              | ✔     | ✘      | ✘        |
| Ver lista de empleados    | ✔     | ✔      | ✔        |
| Crear/Editar/Eliminar tareas | ✔     | ✔      | ✘        |
| Ver todas las tareas      | ✔     | ✔      | ✘        |
| Mover sus propias tareas  | ✔     | ✔      | ✔        |
| Mover tareas de otros     | ✔     | ✔      | ✘        |
| Regresar tarea de Completado (como Empleado) | - | - | ✘ (bloqueado) |

## Instalación

### Requisitos
- PHP 8.2+
- Composer
- Git

### Pasos

```bash
# Clonar el repositorio
git clone https://github.com/WhosMayer/Workify.git
cd Workify

# Instalar dependencias
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Migrar y cargar datos de prueba
php artisan migrate:fresh --seed

# Iniciar servidor
php artisan serve
```

Accede a `http://localhost:8000`

## Usuarios de Prueba

Todos usan la contraseña: `password`

| Correo                | Rol      | Nombre         |
|-----------------------|----------|----------------|
| mayer@admin.com       | Admin    | Mayer Gómez    |
| milton@editor.com     | Editor   | Milton Muñoz   |
| ana@empleado.com      | Empleado | Ana García     |

> **Nota**: Cambia estas credenciales antes de usar en producción.

## Estructura

- `app/Http/Controllers/` — Lógica principal (Dashboard, Kanban, Employees, Tasks)
- `app/Http/Middleware/CheckRole.php` — Middleware para control de roles
- `resources/views/` — Vistas Blade con diseño personalizado
- `database/seeders/DatabaseSeeder.php` — Datos de prueba
- `routes/web.php` — Rutas protegidas por roles

## Documentación

- **Manual de Usuario**: Incluye guías de uso por rol y credenciales de prueba.
- **Manual Técnico**: Arquitectura, modelo de datos, estructura del proyecto y consideraciones de desarrollo.

Los manuales se encuentran fuera del repositorio (en la raíz del proyecto local).

## Tecnologías

- Laravel 12 + Eloquent + SQLite
- Laravel Breeze (solo backend de auth)
- Tailwind CSS + Material Symbols + Inter font
- SortableJS para Kanban
- PHP 8.2+

## Licencia

Proyecto académico para presentación de curso.

---

Desarrollado como proyecto de curso.