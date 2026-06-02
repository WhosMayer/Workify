<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Nueva Contraseña - Workify</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#24aceb",
                        "background-light": "#f6f7f8",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background-light font-display min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-4">
        <!-- Branding -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold tracking-tight text-slate-900 mb-1">Workify</h1>
            <p class="text-sm text-slate-500">Gestor de Empleados</p>
            <p class="text-slate-600 text-sm mt-6 font-bold">Crea tu nueva contraseña</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email (pre-filled from link) -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Correo electrónico</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $request->email) }}" 
                        required 
                        autocomplete="username"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 @error('email') border-red-400 @enderror"
                        placeholder="tu@correo.com"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-5">
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Nueva contraseña</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 @error('password') border-red-400 @enderror"
                        placeholder="Mínimo 8 caracteres"
                    >
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Confirmar nueva contraseña</label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 @error('password_confirmation') border-red-400 @enderror"
                        placeholder="Repite tu nueva contraseña"
                    >
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-primary hover:brightness-110 transition-all text-white font-bold py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-primary/20">
                    <span>Restablecer contraseña</span>
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">
            <a href="{{ route('login') }}" class="text-primary hover:underline font-medium">Volver a iniciar sesión</a>
        </p>
    </div>
</body>
</html>
