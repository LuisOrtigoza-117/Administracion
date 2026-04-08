@extends('layouts.main')

@section('title', 'Recesos')

@section('content')
<style>
@media print {
    .btn, nav, .no-print, form { display: none !important; }
    body { background: white; }
    .card { box-shadow: none; border: 1px solid #ddd; page-break-after: always; }
    table { font-size: 10px; }
    th, td { padding: 4px; }
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <h1><i class="fas fa-coffee"></i> Recesos</h1>
    <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver a Horarios
    </a>
</div>

<div class="alert alert-info no-print">
    <i class="fas fa-info-circle"></i>
    Configure el horario de receso para cada grupo. El receso aparece en la impresión del horario.
</div>

<div class="card mb-4 no-print">
    <div class="card-header bg-warning text-dark">
        <i class="fas fa-plus"></i> Agregar Receso
    </div>
    <div class="card-body">
        <form action="{{ route('schedules.recesses.store') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label for="group_id" class="form-label">Grupo *</label>
                <select class="form-select" id="group_id" name="group_id" required>
                    <option value="">Seleccionar...</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->grade }}° - {{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="day" class="form-label">Día *</label>
                <select class="form-select" id="day" name="day" required>
                    <option value="monday">Lunes</option>
                    <option value="tuesday">Martes</option>
                    <option value="wednesday">Miércoles</option>
                    <option value="thursday">Jueves</option>
                    <option value="friday">Viernes</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="start_time" class="form-label">Hora Inicio *</label>
                <input type="time" class="form-control" name="start_time" required>
            </div>
            <div class="col-md-2">
                <label for="end_time" class="form-label">Hora Fin *</label>
                <input type="time" class="form-control" name="end_time" required>
            </div>
            <div class="col-md-2">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="name" placeholder="Ej: Receso, Recreo">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-warning w-100">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-warning text-dark">
        <div class="d-flex justify-content-between align-items-center no-print">
            <h5 class="mb-0"><i class="fas fa-list"></i> Recesos Configurados</h5>
        </div>
        <h5 class="mb-0 d-print">Recesos Configurados</h5>
    </div>
    <div class="card-body p-0">
        @if($recesses->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-warning">
                        <tr>
                            <th>Grupo</th>
                            <th>Día</th>
                            <th>Hora de Inicio</th>
                            <th>Hora de Fin</th>
                            <th>Duración</th>
                            <th>Nombre</th>
                            <th class="no-print">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recesses as $recess)
                            <tr>
                                <td>{{ $recess->group->grade }}° - {{ $recess->group->name }}</td>
                                <td>
                                    @switch($recess->day)
                                        @case('monday') Lunes @break
                                        @case('tuesday') Martes @break
                                        @case('wednesday') Miércoles @break
                                        @case('thursday') Jueves @break
                                        @case('friday') Viernes @break
                                    @endswitch
                                </td>
                                <td>{{ $recess->start_time }}</td>
                                <td>{{ $recess->end_time }}</td>
                                <td>{{ $recess->duration_minutes }} min</td>
                                <td><span class="badge bg-warning text-dark">{{ $recess->name }}</span></td>
                                <td class="no-print">
                                    <form action="{{ route('schedules.recesses.destroy', $recess) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este receso?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card-body text-center text-muted">
                <i class="fas fa-calendar-times" style="font-size: 48px;"></i>
                <h5 class="mt-3">No hay recesos configurados</h5>
                <p>Use el formulario de arriba para agregar recesos.</p>
            </div>
        @endif
    </div>
</div>

<div class="card mt-4 no-print">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="fas fa-clock"></i> Resumen de Recesos</h5>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-4">
                <h3 class="text-warning">{{ $recesses->count() }}</h3>
                <p>Total de Recesos</p>
            </div>
            <div class="col-md-4">
                <h3 class="text-primary">{{ $recesses->groupBy('group_id')->count() }}</h3>
                <p>Grupos con Receso</p>
            </div>
            <div class="col-md-4">
                <h3 class="text-success">{{ $recesses->avg('duration_minutes') ? round($recesses->avg('duration_minutes')) : 0 }} min</h3>
                <p>Duración Promedio</p>
            </div>
        </div>
    </div>
</div>
@endsection
