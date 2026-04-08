@extends('layouts.main')

@section('title', 'Equipos')

@section('content')
<div class="print-header">
    <h1>INVENTARIO DE EQUIPOS DE CÓMPUTO</h1>
    <p style="text-align: center; font-size: 12px; margin-bottom: 5px;">Laboratorio de Informática</p>
    <p style="text-align: center; font-size: 10px;">Fecha de impresión: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="screen-only"><i class="fas fa-desktop"></i> Equipos</h1>
    <div>
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <a href="{{ route('computers.reports') }}" class="btn btn-warning">
            <i class="fas fa-exclamation-triangle"></i> Reportes
        </a>
        <a href="{{ route('computers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Equipo
        </a>
    </div>
</div>

<style>
@media print {
    @page {
        size: landscape;
        margin: 0.5cm;
    }
    .btn, nav, .no-print { display: none !important; }
    body { background: white; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .card { box-shadow: none; border: 1px solid #ddd; }
    .container-fluid { max-width: 100% !important; padding: 0 !important; }
    .col-md-10 { margin: 0 !important; padding: 0 !important; }
    .main-content { margin-left: 0 !important; }
    
    h1 { font-size: 16px; margin-bottom: 5px; text-align: center; }
    h1.screen-only { display: none; }
    .print-header { display: block !important; margin-bottom: 10px; }
    
    table { font-size: 8px; width: 100%; border-collapse: collapse; }
    thead { display: table-header-group; }
    tr { page-break-inside: avoid; }
    th, td { border: 1px solid #000; text-align: left; padding: 3px 5px; word-break: break-word; white-space: nowrap; }
    th { background-color: #eee !important; font-weight: bold; }
    
    .badge { 
        border: 1px solid #000; 
        padding: 1px 4px; 
        font-size: 7px;
    }
    .bg-success { color: #000 !important; background: #fff !important; }
    .bg-danger { color: #000 !important; background: #fff !important; }
    .bg-warning { color: #000 !important; background: #fff !important; }
    .bg-secondary { color: #000 !important; background: #fff !important; }
    
    .table-responsive { overflow: visible; }
    .row.mb-4 { display: none !important; }
    .card { margin-bottom: 10px; }
}
.print-header { display: none; }
</style>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-check fa-2x text-success mb-2"></i>
                <h5>{{ \App\Models\Computer::where('status', 'functional')->count() }}</h5>
                <p class="text-muted mb-0">Funcionales</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-times fa-2x text-danger mb-2"></i>
                <h5>{{ \App\Models\Computer::where('status', 'damaged')->count() }}</h5>
                <p class="text-muted mb-0">Dañados</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-wrench fa-2x text-warning mb-2"></i>
                <h5>{{ \App\Models\Computer::where('status', 'repairing')->count() }}</h5>
                <p class="text-muted mb-0">En Reparación</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-trash fa-2x text-secondary mb-2"></i>
                <h5>{{ \App\Models\Computer::where('status', 'retired')->count() }}</h5>
                <p class="text-muted mb-0">Dados de Baja</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="equiposTable">
                <thead>
                    <tr>
                        <th class="no-print">Acciones</th>
                        <th>No. PC</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Procesador</th>
                        <th>RAM</th>
                        <th>Almacenamiento</th>
                        <th>S.O.</th>
                        <th>Monitor</th>
                        <th>Teclado</th>
                        <th>Mouse</th>
                        <th>Ubicación</th>
                        <th>Fecha Compra</th>
                        <th>Notas</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($computers as $computer)
                    <tr>
                        <td class="no-print">
                            <a href="{{ route('computers.show', $computer) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('computers.edit', $computer) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('computers.destroy', $computer) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        <td><strong>{{ $computer->pc_number }}</strong></td>
                        <td>{{ $computer->brand ?? '-' }}</td>
                        <td>{{ $computer->model ?? '-' }}</td>
                        <td>{{ $computer->processor ?? '-' }}</td>
                        <td>{{ $computer->ram ?? '-' }}</td>
                        <td>{{ $computer->storage ?? '-' }}</td>
                        <td>{{ $computer->operating_system ?? '-' }}</td>
                        <td>{{ $computer->monitor ?? '-' }}</td>
                        <td>{{ $computer->keyboard ?? '-' }}</td>
                        <td>{{ $computer->mouse ?? '-' }}</td>
                        <td>{{ $computer->location }}</td>
                        <td>{{ $computer->purchase_date ? $computer->purchase_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $computer->notes ?? '-' }}</td>
                        <td>
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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="15" class="text-center text-muted">No hay equipos registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
