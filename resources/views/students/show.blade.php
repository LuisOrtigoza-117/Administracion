@extends('layouts.main')

@section('title', 'Estudiante: ' . $student->fullName)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-user-graduate"></i> {{ $student->fullName }}</h1>
    <a href="{{ route('students.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user"></i> Información Personal</h5>
            </div>
            <div class="card-body">
                <p><strong>No. Control:</strong> {{ $student->student_number }}</p>
                <p><strong>Nombre:</strong> {{ $student->name }}</p>
                <p><strong>Apellido:</strong> {{ $student->lastname }}</p>
                <p><strong>Email:</strong> <a href="mailto:{{ $student->email }}">{{ $student->email }}</a></p>
                @if($student->phone)
                <p><strong>Teléfono:</strong> <a href="tel:{{ $student->phone }}">{{ $student->phone }}</a></p>
                @else
                <p><strong>Teléfono:</strong> No registrado</p>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-users"></i> Información Académica</h5>
            </div>
            <div class="card-body">
                <p><strong>Grupo:</strong> {{ $student->group->name ?? 'Sin grupo' }}</p>
                @if($student->group && $student->group->teacher)
                <hr>
                <p class="mb-1"><strong>Docente Asignado:</strong></p>
                <p class="mb-1">{{ $student->group->teacher->name }}</p>
                <p class="mb-1"><small class="text-muted">{{ $student->group->teacher->specialty ?? '' }}</small></p>
                <p class="mb-0"><a href="mailto:{{ $student->group->teacher->email }}">{{ $student->group->teacher->email }}</a></p>
                @if($student->group->teacher->phone)
                <p class="mb-0"><a href="tel:{{ $student->group->teacher->phone }}">{{ $student->group->teacher->phone }}</a></p>
                @endif
                @else
                <p><strong>Docente:</strong> Sin asignar</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Historial de Asistencia</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Hora Llegada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                <td>
                                    @switch($attendance->status)
                                        @case('present')
                                            <span class="badge bg-success">Presente</span>
                                            @break
                                        @case('absent')
                                            <span class="badge bg-danger">Ausente</span>
                                            @break
                                        @case('late')
                                            <span class="badge bg-warning">Retardo</span>
                                            @break
                                        @case('justified')
                                            <span class="badge bg-info">Justificado</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $attendance->arrival_time ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No hay registros de asistencia</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tareas Entregadas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tarea</th>
                                <th>Fecha Entrega</th>
                                <th>Estado</th>
                                <th>Calificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->taskSubmissions as $submission)
                            <tr>
                                <td>{{ $submission->task->title ?? 'Tarea eliminada' }}</td>
                                <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    @switch($submission->status)
                                        @case('pending')
                                            <span class="badge bg-secondary">Pendiente</span>
                                            @break
                                        @case('submitted')
                                            <span class="badge bg-info">Entregado</span>
                                            @break
                                        @case('graded')
                                            <span class="badge bg-success">Calificado</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    @if($submission->grade !== null)
                                        {{ $submission->grade }}/{{ $submission->task->max_points ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No hay tareas entregadas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
