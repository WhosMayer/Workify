<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'employee_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación: Un usuario PUEDE estar vinculado a un empleado.
     */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class);
    }

    // Helpers de rol y permisos

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    public function isEmpleado(): bool
    {
        return $this->role === 'empleado';
    }

    /**
     * Puede ver la sección de Empleados
     */
    public function canViewEmployees(): bool
    {
        return true;
    }

    /**
     * Puede crear, editar y eliminar empleados.
     * Solo disponible para administradores.
     */
    public function canManageEmployees(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Puede crear nuevas tareas y asignarlas.
     * Solo admin y editor pueden crear tareas.
     */
    public function canCreateTasks(): bool
    {
        return in_array($this->role, ['admin', 'editor']);
    }
}