@extends('layouts.app')
@section('title', 'Tareas')
@section('page-title', $board->name)
@section('page-subtitle', 'Gestiona y arrastra tareas entre columnas.')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        @foreach($board->columns as $col)
        <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-500">
            <span class="w-2.5 h-2.5 rounded-full" style="background:{{ $col->color }}"></span>
            {{ $col->name }} ({{ $col->tasks->count() }})
        </span>
        @endforeach
    </div>
    <a href="{{ route('tasks.create') }}"
       class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-bold hover:brightness-110 transition-all shadow-lg shadow-primary/20">
        <span class="material-symbols-outlined text-xl">add_task</span>
        Nueva Tarea
    </a>
</div>

{{-- TABLERO --}}
<div class="flex gap-5 overflow-x-auto pb-4 items-start">
    @foreach($board->columns as $column)
    <div class="flex-shrink-0 w-72">

        {{-- Cabecera columna --}}
        <div class="flex items-center gap-2 mb-3 px-1">
            <div class="w-3 h-3 rounded-full" style="background-color: {{ $column->color }}"></div>
            <h2 class="font-bold text-slate-700 text-sm">{{ $column->name }}</h2>
            <span class="ml-auto text-xs bg-slate-200 text-slate-600 rounded-full px-2 py-0.5 font-semibold">
                {{ $column->tasks->count() }}
            </span>
        </div>

        {{-- Zona de tarjetas --}}
        <div class="kanban-column bg-slate-100/80 rounded-2xl p-3 min-h-32 space-y-3"
             data-column-id="{{ $column->id }}">

            @foreach($column->tasks as $task)
            <div class="task-card bg-white rounded-xl border border-slate-200 p-4 cursor-grab
                        hover:shadow-md hover:border-primary/30 transition-all select-none"
                 data-task-id="{{ $task->id }}">

                {{-- Header tarjeta --}}
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-semibold text-slate-800 text-sm leading-snug flex-1 pr-2">{{ $task->title }}</h3>
                    <div class="relative flex-shrink-0">
                        <button onclick="toggleMenu(this); event.stopPropagation();"
                                class="text-slate-300 hover:text-slate-600 transition p-1 rounded-lg hover:bg-slate-100">
                            <span class="material-symbols-outlined text-lg">more_vert</span>
                        </button>
                        <div class="task-menu hidden absolute right-0 top-7 bg-white border border-slate-200 rounded-xl shadow-lg z-20 min-w-32 py-1 overflow-hidden">
                            <a href="{{ route('tasks.edit', $task) }}"
                               class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                                <span class="material-symbols-outlined text-lg">edit</span> Editar
                            </a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta tarea?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50">
                                    <span class="material-symbols-outlined text-lg">delete</span> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                @if($task->description)
                <p class="text-xs text-slate-400 mb-3 line-clamp-2">{{ $task->description }}</p>
                @endif

                {{-- Badges --}}
                <div class="flex items-center gap-2 flex-wrap mb-3">
                    @php
                        $pc = ['high' => ['bg-red-100','text-red-700','🔴'], 'medium' => ['bg-amber-100','text-amber-700','🟡'], 'low' => ['bg-emerald-100','text-emerald-700','🟢']];
                        [$bg, $tc, $icon] = $pc[$task->priority] ?? ['bg-slate-100','text-slate-600','⚪'];
                    @endphp
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $bg }} {{ $tc }}">
                        {{ $icon }} {{ ucfirst($task->priority) }}
                    </span>
                    @if($task->due_date)
                    @php $overdue = $task->due_date->isPast() && $column->name !== 'Completado'; @endphp
                    <span class="text-xs {{ $overdue ? 'text-red-500 font-bold' : 'text-slate-400' }} flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                        {{ $task->due_date->format('d/m/Y') }}
                        @if($overdue) ⚠️ @endif
                    </span>
                    @endif
                </div>

                {{-- Empleado --}}
                <div class="flex items-center gap-2 pt-2 border-t border-slate-100">
                    <div class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($task->employee->name, 0, 1)) }}
                    </div>
                    <a href="{{ route('employees.show', $task->employee) }}"
                       class="text-xs text-slate-500 hover:text-primary truncate transition"
                       onclick="event.stopPropagation()">
                        {{ $task->employee->name }}
                    </a>
                </div>
            </div>
            @endforeach

            @if($column->tasks->isEmpty())
            <div class="empty-placeholder text-center py-8 text-slate-400 text-xs border-2 border-dashed border-slate-300 rounded-xl">
                <span class="material-symbols-outlined block text-3xl mb-1 opacity-40">inbox</span>
                Sin tareas
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

document.querySelectorAll('.kanban-column').forEach(column => {
    Sortable.create(column, {
        group: 'tasks',
        animation: 150,
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag',
        onEnd: function(evt) {
            const taskId   = evt.item.dataset.taskId;
            const columnId = evt.to.dataset.columnId;
            const position = evt.newIndex;
            updatePlaceholders();
            fetch(`/tasks/${taskId}/move`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ column_id: columnId, position: position })
            }).then(r => r.json()).catch(console.error);
        }
    });
});

function toggleMenu(btn) {
    const menu = btn.nextElementSibling;
    document.querySelectorAll('.task-menu').forEach(m => { if (m !== menu) m.classList.add('hidden'); });
    menu.classList.toggle('hidden');
}

document.addEventListener('click', () => {
    document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));
});

function updatePlaceholders() {
    document.querySelectorAll('.kanban-column').forEach(col => {
        const cards = col.querySelectorAll('.task-card');
        let ph = col.querySelector('.empty-placeholder');
        if (cards.length === 0) {
            if (!ph) {
                ph = document.createElement('div');
                ph.className = 'empty-placeholder text-center py-8 text-slate-400 text-xs border-2 border-dashed border-slate-300 rounded-xl';
                ph.innerHTML = '<span class="material-symbols-outlined block text-3xl mb-1 opacity-40">inbox</span>Sin tareas';
                col.appendChild(ph);
            }
            ph.classList.remove('hidden');
        } else if (ph) {
            ph.classList.add('hidden');
        }
    });
}
</script>
@endsection
