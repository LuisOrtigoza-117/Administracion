@extends('layouts.main')

@section('title', 'Reportes de Equipos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-exclamation-triangle"></i> Reportes de Equipos</h1>
    <div>
        <a href="{{ route('computers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Equipos
        </a>
        <a href="{{ route('computers.reports.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Reporte
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Equipo</th>
                        <th>Descripción</th>
                        <th>Reportó</th>
                        <th>Fecha Reporte</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <td>{{ $report->computer->pc_number }}</td>
                        <td>{{ Str::limit($report->description, 50) }}</td>
                        <td>{{ $report->reported_by }}</td>
                        <td>{{ $report->report_date->format('d/m/Y') }}</td>
                        <td>
                            @switch($report->status)
                                @case('pending')
                                    <span class="badge bg-warning">Pendiente</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge bg-info">En Progreso</span>
                                    @break
                                @case('resolved')
                                    <span class="badge bg-success">Resuelto</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            @if($report->status != 'resolved')
                            <form action="{{ route('computers.reports.resolve', $report) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Marcar como resuelto?')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('computers.show', $report->computer) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay reportes</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
