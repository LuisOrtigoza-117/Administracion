@extends('layouts.main')

@section('title', 'Grupo: ' . $group->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-users"></i> {{ $group->name }}</h1>
    <a href="{{ route('groups.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Información del Grupo</h5>
                <p><strong>Grado:</strong> {{ $group->grade }}</p>
                <p><strong>Sección:</strong> {{ $group->section }}</p>
                <p><strong>Ciclo Escolar:</strong> {{ $group->school_year }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Estudiantes ({{ $group->students->count() }})</h5>
                <a href="{{ route('students.create', ['group_id' => $group->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Agregar Estudiante
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No. Control</th>
                                <th>Nombre</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($group->students as $student)
                            <tr>
                                <td>{{ $student->student_number }}</td>
                                <td>{{ $student->name }} {{ $student->lastname }}</td>
                                <td>{{ $student->email }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No hay estudiantes registrados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tareas ({{ $group->tasks->count() }})</h5>
        <a href="{{ route('tasks.create', ['group_id' => $group->id]) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Nueva Tarea
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Fecha Entrega</th>
                        <th>Puntos</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($group->tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->due_date->format('d/m/Y') }}</td>
                        <td>{{ $task->max_points }}</td>
                        <td>
                            @if($task->due_date->isPast())
                                <span class="badge bg-danger">Vencida</span>
                            @else
                                <span class="badge bg-success">Activa</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No hay tareas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
