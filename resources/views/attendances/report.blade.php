@extends('layouts.main')

@section('title', 'Reportes de Asistencia')

@section('content')
<style>
@media print {
    .btn, .no-print { display: none !important; }
    body { background: white; }
    .card { box-shadow: none; border: 1px solid #ddd; page-break-inside: avoid; }
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-chart-bar"></i> Reportes de Asistencia</h1>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-info">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="card mb-4 no-print">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="fas fa-filter"></i> Seleccionar Grupos para Imprimir</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('attendances.report') }}" class="row g-3">
            <div class="col-md-3">
                <label for="group_ids" class="form-label">Grupos</label>
                <select class="form-select" id="group_ids" name="group_ids[]" multiple size="5">
                    @php
                    $selectedGroupIds = request()->get('group_ids', []);
                    @endphp
                    @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ in_array($group->id, $selectedGroupIds) ? 'selected' : '' }}>
                        {{ $group->grade }}° - {{ $group->name }}
                    </option>
                    @endforeach
                </select>
                <small class="text-muted">Mantén Ctrl/Cmd para seleccionar varios</small>
            </div>
            <div class="col-md-3">
                <label for="start_date" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                <a href="{{ route('attendances.report') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

@php
$selectedGroupIds = request()->get('group_ids', []);
$groupedStudents = $students->groupBy('group_id');

if (!empty($selectedGroupIds)) {
    $groupedStudents = $groupedStudents->filter(function($value, $key) use ($selectedGroupIds) {
        return in_array($key, $selectedGroupIds);
    });
}
@endphp

@forelse($groupedStudents as $groupId => $groupStudents)
    @php
    $group = $groups->find($groupId);
    @endphp
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-users"></i> {{ $group->grade ?? '' }}° Año - Grupo {{ $group->name ?? 'Sin grupo' }}
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th class="text-center">Presentes</th>
                            <th class="text-center">Ausentes</th>
                            <th class="text-center">Retardos</th>
                            <th class="text-center">Justificados</th>
                            <th class="text-center">% Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupStudents as $student)
                        @php
                            $present = $attendances->where('student_id', $student->id)->where('status', 'present')->count();
                            $absent = $attendances->where('student_id', $student->id)->where('status', 'absent')->count();
                            $late = $attendances->where('student_id', $student->id)->where('status', 'late')->count();
                            $justified = $attendances->where('student_id', $student->id)->where('status', 'justified')->count();
                            $total = $present + $absent + $late + $justified;
                            $percentage = $total > 0 ? round(($present + $late + $justified) / $total * 100, 1) : 0;
                            $barColor = $percentage >= 90 ? 'bg-success' : ($percentage >= 75 ? 'bg-warning' : 'bg-danger');
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $student->name }} {{ $student->lastname }}</strong>
                                <br><small class="text-muted">No. Control: {{ $student->student_number ?? 'N/A' }}</small>
                            </td>
                            <td class="text-center"><span class="badge bg-success">{{ $present }}</span></td>
                            <td class="text-center"><span class="badge bg-danger">{{ $absent }}</span></td>
                            <td class="text-center"><span class="badge bg-warning text-dark">{{ $late }}</span></td>
                            <td class="text-center"><span class="badge bg-info">{{ $justified }}</span></td>
                            <td class="text-center">
                                <div class="progress" style="height: 20px; min-width: 100px;">
                                    <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ $percentage }}%">
                                        {{ $percentage }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No hay datos de asistencia para el período seleccionado o no se seleccionaron grupos.
    </div>
@endforelse

<div class="no-print text-center text-muted mt-4">
    <p>Reporte generado: {{ now()->format('d/m/Y H:i') }}</p>
</div>
@endsection
