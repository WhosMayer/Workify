<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Verifica que el usuario autenticado tenga uno de los roles permitidos.
     *
     * Uso en rutas:
     * Route::middleware('role:admin,editor')->group(...);
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Si no se pasaron roles, permitir
        if (empty($roles)) {
            return $next($request);
        }

        // Verificar si el rol del usuario está en la lista permitida
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Sin permiso
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}
