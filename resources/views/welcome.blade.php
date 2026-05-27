<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GestorEmpleados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white flex items-center justify-center min-h-screen">
    <div class="text-center">
        <h1 class="text-4xl font-bold mb-4">Gestor de Empleados</h1>
        <p class="text-slate-400 mb-8">Sistema de gestión con Kanban</p>
        
        @auth
            <a href="{{ route('dashboard') }}" 
               class="inline-block bg-primary hover:bg-primary/90 text-white px-8 py-3 rounded-xl font-semibold transition">
                Ir al Panel
            </a>
        @else
            <div class="flex gap-4 justify-center">
                <a href="{{ route('login') }}" 
                   class="inline-block bg-white text-slate-900 px-8 py-3 rounded-xl font-semibold hover:bg-slate-100 transition">
                    Iniciar Sesión
                </a>
                <a href="{{ route('register') }}" 
                   class="inline-block border border-white px-8 py-3 rounded-xl font-semibold hover:bg-white hover:text-slate-900 transition">
                    Registrarse
                </a>
            </div>
        @endauth
    </div>
</body>
</html>
