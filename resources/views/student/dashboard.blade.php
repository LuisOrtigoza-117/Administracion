@extends('student.layout', ['student' => $student])

@section('title', 'Dashboard - Estudiante')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Bienvenido, {{ $student->fullName }}</h2>
        <p class="text-muted">Grupo: {{ $student->group->name ?? 'Sin grupo asignado' }}</p>
    </div>
</div>

@if($student->group && $student->group->teacher)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i> Mi Docente</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong><i class="fas fa-user me-2"></i>Nombre:</strong> {{ $student->group->teacher->name }}</p>
                        <p class="mb-1"><strong><i class="fas fa-briefcase me-2"></i>Especialidad:</strong> {{ $student->group->teacher->specialty ?? 'No especificada' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong><i class="fas fa-envelope me-2"></i>Email:</strong> 
                            <a href="mailto:{{ $student->group->teacher->email }}">{{ $student->group->teacher->email }}</a>
                        </p>
                        @if($student->group->teacher->phone)
                        <p class="mb-0"><strong><i class="fas fa-phone me-2"></i>Teléfono:</strong> 
                            <a href="tel:{{ $student->group->teacher->phone }}">{{ $student->group->teacher->phone }}</a>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Tareas Pendientes</h6>
                    <h3 class="mb-0">{{ $tasks->where('due_date', '>=', now())->count() }}</h3>
                </div>
                <div class="fs-1 text-primary"><i class="fas fa-tasks"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Asistencias Este Mes</h6>
                    <h3 class="mb-0">{{ $recentAttendances->where('status', 'present')->count() }}</h3>
                </div>
                <div class="fs-1 text-success"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Tareas Entregadas</h6>
                    <h3 class="mb-0">{{ $tasks->filter(function($t) { return $t->submissions->first(); })->count() }}</h3>
                </div>
                <div class="fs-1 text-info"><i class="fas fa-file-upload"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-tasks me-2"></i> Tareas Recientes</h5>
            </div>
            <div class="card-body">
                @if($tasks->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($tasks as $task)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $task->title }}</h6>
                                    <small class="text-muted">Fecha límite: {{ $task->due_date->format('d/m/Y') }}</small>
                                </div>
                                <span class="badge bg-{{ $task->submissions->first() ? 'success' : 'warning' }}">
                                    {{ $task->submissions->first() ? 'Entregada' : 'Pendiente' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 text-end">
                        <a href="{{ route('student.tasks') }}" class="btn btn-sm btn-student">Ver todas las tareas</a>
                    </div>
                @else
                    <p class="text-muted mb-0">No hay tareas asignadas a tu grupo.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i> Asistencia Reciente</h5>
            </div>
            <div class="card-body">
                @if($recentAttendances->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentAttendances as $attendance)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $attendance->date->format('d/m/Y') }}</h6>
                                    <small class="text-muted">{{ $attendance->group->name ?? '' }}</small>
                                </div>
                                <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'late' ? 'warning' : 'danger') }}">
                                    {{ $attendance->status == 'present' ? 'Presente' : ($attendance->status == 'late' ? 'Tarde' : 'Ausente') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 text-end">
                        <a href="{{ route('student.attendance') }}" class="btn btn-sm btn-success">Ver historial completo</a>
                    </div>
                @else
                    <p class="text-muted mb-0">No hay registros de asistencia.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
