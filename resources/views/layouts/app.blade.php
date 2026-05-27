<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Panel') - GestorEmpleados</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                "primary": "#24aceb",
                "background-light": "#f6f7f8",
            },
            fontFamily: { "display": ["Inter", "sans-serif"] },
            borderRadius: {"DEFAULT": "0.5rem", "lg": "1rem", "xl": "1.5rem", "full": "9999px"},
        },
    },
}
</script>
<style>
.material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
.sortable-ghost { opacity: 0.4; background: #bae6fd; border: 2px dashed #0ea5e9; border-radius: 12px; }
.sortable-drag { transform: rotate(2deg); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
</style>
</head>
<body class="bg-background-light font-display text-slate-900 min-h-screen">
<div class="flex h-screen overflow-hidden">

{{-- SIDEBAR --}}
<aside class="w-72 bg-white border-r border-slate-200 flex flex-col flex-shrink-0">
    <div class="p-6 flex items-center gap-3">
        <div class="bg-primary p-2 rounded-lg text-white">
            <span class="material-symbols-outlined text-2xl">group_work</span>
        </div>
        <div>
             <h1 class="text-lg font-bold tracking-tight">GestorEmpleados</h1>
             <p class="text-xs text-slate-500">
                 @if(auth()->user()?->isAdmin())
                 Admin Portal
                 @elseif(auth()->user()?->isEditor())
                 Editor Portal
                 @else
                 Portal de Empleado
                 @endif
            </p>
        </div>
    </div>

    <nav class="flex-1 px-4 space-y-1">
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
           {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span>Panel</span>
        </a>

        {{-- Empleados: visible para todos según enfoque B (admin, editor, empleado) --}}
        @if(auth()->user()?->canViewEmployees())
        <a href="{{ route('employees.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
           {{ request()->routeIs('employees.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
            <span class="material-symbols-outlined">badge</span>
            <span>Empleados</span>
        </a>
        @endif

        <a href="{{ route('kanban.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
           {{ request()->routeIs('kanban.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
            <span class="material-symbols-outlined">view_kanban</span>
            <span>Tareas</span>
        </a>

        {{-- Nueva Tarea: admin + editor (gestionan tareas) --}}
        @if(auth()->user()?->isAdmin() || auth()->user()?->isEditor())
        <a href="{{ route('tasks.create') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
           {{ request()->routeIs('tasks.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
            <span class="material-symbols-outlined">task_alt</span>
            <span>Nueva Tarea</span>
        </a>
        @endif
    </nav>

    <div class="px-4 py-4 border-t border-slate-200 mt-auto">
        <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50">
            <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="overflow-hidden flex-1">
                <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email ?? 'admin@empresa.com' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                        title="Cerrar sesión">
                    <span class="material-symbols-outlined text-xl">logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- MAIN --}}
<main class="flex-1 overflow-y-auto bg-background-light">
    {{-- HEADER --}}
    <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-200 px-8 py-4 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">@yield('page-title', 'Panel')</h2>
            <p class="text-sm text-slate-500">@yield('page-subtitle', 'Bienvenido de nuevo.')</p>
        </div>
        <div class="flex items-center gap-4">
            <label class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3 text-slate-400 text-xl">search</span>
                <input class="pl-10 pr-4 py-2 bg-slate-100 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary w-64" placeholder="Buscar datos..." type="text"/>
            </label>
            <button class="p-2 bg-slate-100 rounded-xl text-slate-600 hover:bg-primary/10 hover:text-primary transition-all">
                <span class="material-symbols-outlined">notifications</span>
            </button>
        </div>
    </header>

    {{-- Mensajes flash --}}
    <div class="px-8 pt-4">
        @if(session('success'))
            <div class="mb-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex justify-between items-center text-sm">
                <span>✅ {{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="font-bold ml-4 hover:text-emerald-900">✕</button>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                ❌ {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="p-8 max-w-7xl mx-auto">
        @yield('content')
    </div>
</main>

</div>
</body>
</html>
