@extends('layouts.main')

@section('title', 'Equipo: ' . $computer->pc_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-desktop"></i> {{ $computer->pc_number }}</h1>
    <a href="{{ route('computers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información</h5>
            </div>
            <div class="card-body">
                <p><strong>Número de PC:</strong> {{ $computer->pc_number }}</p>
                <p><strong>Ubicación:</strong> {{ $computer->location }}</p>
                <p>
                    <strong>Estado:</strong>
                    @switch($computer->status)
                        @case('functional')
                            <span class="badge bg-success">Funcional</span>
                            @break
                        @case('damaged')
                            <span class="badge bg-danger">Dañado</span>
                            @break
                        @case('repairing')
                            <span class="badge bg-warning">En Reparación</span>
                            @break
                        @case('retired')
                            <span class="badge bg-secondary">Dado de Baja</span>
                            @break
                    @endswitch
                </p>
                @if($computer->purchase_date)
                    <p><strong>Fecha de Compra:</strong> {{ $computer->purchase_date->format('d/m/Y') }}</p>
                @endif
            </div>
        </div>

        @if($computer->notes)
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notas</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $computer->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-microchip"></i> Especificaciones</h5>
            </div>
            <div class="card-body">
                <p><strong>Marca:</strong> {{ $computer->brand ?? 'No especificado' }}</p>
                <p><strong>Modelo:</strong> {{ $computer->model ?? 'No especificado' }}</p>
                <p><strong>Procesador:</strong> {{ $computer->processor ?? 'No especificado' }}</p>
                <p><strong>RAM:</strong> {{ $computer->ram ?? 'No especificado' }}</p>
                <p><strong>Almacenamiento:</strong> {{ $computer->storage ?? 'No especificado' }}</p>
                <p><strong>Sistema Operativo:</strong> {{ $computer->operating_system ?? 'No especificado' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-desktop"></i> Periféricos</h5>
            </div>
            <div class="card-body">
                <p><strong>Monitor:</strong> {{ $computer->monitor ?? 'No especificado' }}</p>
                <p><strong>Teclado:</strong> {{ $computer->keyboard ?? 'No especificado' }}</p>
                <p><strong>Mouse:</strong> {{ $computer->mouse ?? 'No especificado' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Historial de Reportes ({{ $computer->reports->count() }})</h5>
                <a href="{{ route('computers.reports.create', ['computer_id' => $computer->id]) }}" class="btn btn-sm btn-danger">
                    <i class="fas fa-plus"></i> Reportar Daño
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Reportó</th>
                                <th>Estado</th>
                                <th>Resolución</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($computer->reports as $report)
                            <tr>
                                <td>{{ $report->report_date->format('d/m/Y') }}</td>
                                <td>{{ Str::limit($report->description, 50) }}</td>
                                <td>{{ $report->reported_by }}</td>
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
                                <td>{{ $report->resolved_date ? $report->resolved_date->format('d/m/Y') : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No hay reportes</td>
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
