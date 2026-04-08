@extends('layouts.main')

@section('title', 'Grupos')

@section('content')
<style>
@media print {
    .btn, nav { display: none !important; }
    body { background: white; }
    .card { box-shadow: none; border: 1px solid #ddd; }
    h1 { font-size: 18px; text-align: center; }
    table { font-size: 11px; width: 100%; }
    th, td { padding: 6px; text-align: left; }
    .print-header { display: block !important; margin-bottom: 15px; }
    .screen-header { display: none !important; }
    .no-print { display: none !important; }
}
.print-header { display: none; }
</style>

<div class="print-header">
    <h1>LISTADO DE GRUPOS</h1>
    <p style="text-align: center; font-size: 12px;">Fecha de impresión: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 screen-header">
    <h1><i class="fas fa-users"></i> Grupos</h1>
    <div>
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <a href="{{ route('groups.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Grupo
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Grado</th>
                        <th>Sección</th>
                        <th>Ciclo Escolar</th>
                        <th>Docente</th>
                        <th class="no-print">Estudiantes</th>
                        <th class="no-print">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groups as $group)
                    <tr>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->grade }}</td>
                        <td>{{ $group->section }}</td>
                        <td>{{ $group->school_year }}</td>
                        <td>{{ $group->teacher->name ?? 'Sin asignar' }}</td>
                        <td class="no-print"><span class="badge bg-info">{{ $group->students->count() }}</span></td>
                        <td class="no-print">
                            <a href="{{ route('groups.show', $group) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('groups.edit', $group) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('groups.destroy', $group) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay grupos registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
