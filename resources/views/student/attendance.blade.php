@extends('student.layout', ['student' => $student])

@section('title', 'Mi Asistencia')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Mi Asistencia</h2>
        <p class="text-muted">Grupo: {{ $student->group->name ?? 'Sin grupo asignado' }}</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h6 class="text-muted">Total Días</h6>
                <h2 class="mb-0">{{ $totalDays }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h6 class="text-muted">Presentes</h6>
                <h2 class="mb-0 text-success">{{ $presentDays }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h6 class="text-muted">Tarde</h6>
                <h2 class="mb-0 text-warning">{{ $lateDays }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h6 class="text-muted">Ausentes</h6>
                <h2 class="mb-0 text-danger">{{ $absentDays }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h5 class="mb-0">Porcentaje de Asistencia</h5>
            </div>
            <div class="card-body text-center">
                <div class="display-4 mb-3">{{ $attendancePercentage }}%</div>
                @php
                    $presentWidth = $totalDays > 0 ? ($presentDays / $totalDays * 100) : 0;
                    $lateWidth = $totalDays > 0 ? ($lateDays / $totalDays * 100) : 0;
                    $absentWidth = $totalDays > 0 ? ($absentDays / $totalDays * 100) : 0;
                @endphp
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $presentWidth }}%">
                        Presente
                    </div>
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $lateWidth }}%">
                        Tarde
                    </div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $absentWidth }}%">
                        Ausente
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Historial de Asistencia</h5>
    </div>
    <div class="card-body">
        @if($attendances->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Grupo</th>
                            <th>Estado</th>
                            <th>Hora de llegada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                <td>{{ $attendance->group->name ?? '' }}</td>
                                <td>
                                    @if($attendance->status == 'present')
                                        <span class="badge bg-success">Presente</span>
                                    @elseif($attendance->status == 'late')
                                        <span class="badge bg-warning">Tarde</span>
                                    @else
                                        <span class="badge bg-danger">Ausente</span>
                                    @endif
                                </td>
                                <td>{{ $attendance->time ? \Carbon\Carbon::parse($attendance->time)->format('H:i') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted text-center mb-0">No hay registros de asistencia.</p>
        @endif
    </div>
</div>
@endsection
