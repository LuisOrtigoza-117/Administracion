@extends('layouts.main')

@section('title', 'Tareas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-tasks"></i> Tareas</h1>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nueva Tarea
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="group_id" class="form-label">Grupo</label>
                <select class="form-select" id="group_id" name="group_id">
                    <option value="">Todos los grupos</option>
                    @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ $groupId == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Grupo</th>
                        <th>Fecha Entrega</th>
                        <th>Puntos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>
                            @php
                            $taskGroups = \App\Models\Group::whereIn('id', $task->group_ids ?? [])->get();
                            @endphp
                            @foreach($taskGroups as $g)
                                <span class="badge bg-primary me-1">{{ $g->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $task->due_date->format('d/m/Y') }}</td>
                        <td>{{ $task->max_points }}</td>
                        <td>
                            @if($task->due_date->isPast())
                                <span class="badge bg-danger">Vencida</span>
                            @else
                                <span class="badge bg-success">Activa</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay tareas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
