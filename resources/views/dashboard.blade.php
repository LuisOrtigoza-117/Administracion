@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <p class="text-muted">Resumen del laboratorio de informática</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-users fa-2x text-primary mb-3"></i>
                <h3>{{ $totalGroups }}</h3>
                <p class="text-muted mb-0">Grupos Activos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-user-graduate fa-2x text-success mb-3"></i>
                <h3>{{ $totalStudents }}</h3>
                <p class="text-muted mb-0">Estudiantes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-desktop fa-2x text-info mb-3"></i>
                <h3>{{ $totalComputers }}</h3>
                <p class="text-muted mb-0">Equipos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                <h3>{{ $damagedComputers }}</h3>
                <p class="text-muted mb-0">Equipos Dañados</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-clipboard-check"></i> Asistencia de Hoy</h5>
            </div>
            <div class="card-body">
                <p class="mb-1">Total registradas: <strong>{{ $todayAttendances }}</strong></p>
                <p class="mb-1">Presentes: <strong class="text-success">{{ $presentToday }}</strong></p>
                <a href="{{ route('attendances.index') }}" class="btn btn-primary btn-sm mt-2">
                    <i class="fas fa-clipboard-check"></i> Tomar Asistencia
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-tasks"></i> Tareas Pendientes</h5>
            </div>
            <div class="card-body">
                <p class="mb-1">Tareas activas: <strong>{{ $pendingTasks }}</strong></p>
                <a href="{{ route('tasks.index') }}" class="btn btn-info btn-sm mt-2">
                    <i class="fas fa-tasks"></i> Ver Tareas
                </a>
            </div>
        </div>
    </div>
</div>

@if($recentReports->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-circle"></i> Reportes Recientes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th>Descripción</th>
                                <th>Reportado por</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentReports as $report)
                            <tr>
                                <td>{{ $report->computer->pc_number }}</td>
                                <td>{{ Str::limit($report->description, 50) }}</td>
                                <td>{{ $report->reported_by }}</td>
                                <td>{{ $report->report_date->format('d/m/Y') }}</td>
                                <td>
                                    @if($report->status == 'pending')
                                        <span class="badge bg-warning">Pendiente</span>
                                    @elseif($report->status == 'in_progress')
                                        <span class="badge bg-info">En Progreso</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('computers.reports') }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-eye"></i> Ver Todos los Reportes
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
