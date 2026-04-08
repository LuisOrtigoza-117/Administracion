@extends('layouts.main')

@section('title', 'Asistencia')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-clipboard-check"></i> Asistencia</h1>
    <div>
        <a href="{{ route('attendances.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tomar Asistencia
        </a>
        <a href="{{ route('attendances.report') }}" class="btn btn-info">
            <i class="fas fa-chart-bar"></i> Reportes
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('attendances.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="date" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
            </div>
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
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Grupo</th>
                        <th>Estudiante</th>
                        <th>Estado</th>
                        <th>Hora Llegada</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->date->format('d/m/Y') }}</td>
                        <td>{{ $attendance->group->name }}</td>
                        <td>{{ $attendance->student->name }} {{ $attendance->student->lastname }}</td>
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
                        <td colspan="5" class="text-center text-muted">No hay registros de asistencia para esta fecha</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
