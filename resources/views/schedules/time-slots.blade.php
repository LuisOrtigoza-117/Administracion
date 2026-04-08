@extends('layouts.main')

@section('title', 'Módulos de Tiempo')

@section('content')
<style>
@media print {
    .btn, nav, form, .no-print { display: none !important; }
    body { background: white; }
    .card { box-shadow: none; border: 1px solid #ddd; }
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-clock"></i> Módulos de Tiempo</h1>
    <div>
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i>
    <strong>Configure la duración de cada módulo/hora de clase.</strong>
    Los cambios se reflejarán en todos los horarios. Puede tener múltiples recesos con diferentes nombres y duraciones.
</div>

<div class="alert alert-warning no-print">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>Nota sobre recesos:</strong> Los recesos son módulos especiales donde no se imparten clases. 
    Puede agregar tantos recesos como necesite (Ej: Receso 1, Receso 2, Recreo, etc.).
</div>

<div class="card mb-4 no-print">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-plus"></i> Agregar Nuevo Módulo</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('schedules.time-slots.store') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-2">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="name" required placeholder="Ej: 1ª Hora">
            </div>
            <div class="col-md-1">
                <label for="hour_number" class="form-label">No.</label>
                <input type="number" class="form-control" name="hour_number" min="1" max="20" required>
            </div>
            <div class="col-md-2">
                <label for="start_time" class="form-label">Hora Inicio</label>
                <input type="time" class="form-control" name="start_time" required>
            </div>
            <div class="col-md-2">
                <label for="end_time" class="form-label">Hora Fin</label>
                <input type="time" class="form-control" name="end_time" required>
            </div>
            <div class="col-md-2">
                <label for="duration_minutes" class="form-label">Duración (min)</label>
                <input type="number" class="form-control" name="duration_minutes" min="1" required placeholder="50">
            </div>
            <div class="col-md-2">
                <label for="type" class="form-label">Tipo</label>
                <select class="form-select" name="type" required>
                    <option value="class">Clase</option>
                    <option value="recess">Receso</option>
                    <option value="lunch">Comida</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Módulos Configurados</h5>
            <form action="{{ route('schedules.time-slots.init') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('¿Restablecer a valores predeterminados?')">
                    <i class="fas fa-undo"></i> Restablecer Valores
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No.</th>
                        <th>Nombre</th>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Duración</th>
                        <th>Tipo</th>
                        <th class="no-print">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($slots as $slot)
                        <tr class="{{ $slot->type == 'recess' ? 'table-warning' : ($slot->type == 'lunch' ? 'table-info' : '') }}">
                            <td class="text-center fw-bold">{{ $slot->hour_number }}</td>
                            <td class="fw-bold">{{ $slot->name }}</td>
                            <td class="text-center">{{ $slot->start_time }}</td>
                            <td class="text-center">{{ $slot->end_time }}</td>
                            <td class="text-center">{{ $slot->duration_minutes }} min</td>
                            <td class="text-center">
                                @switch($slot->type)
                                    @case('class')
                                        <span class="badge bg-primary">Clase</span>
                                        @break
                                    @case('recess')
                                        <span class="badge bg-warning text-dark">Receso</span>
                                        @break
                                    @case('lunch')
                                        <span class="badge bg-info">Comida</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="text-center no-print">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editSlot{{ $slot->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('schedules.time-slots.destroy', $slot) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este módulo?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editSlot{{ $slot->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Editar {{ $slot->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('schedules.time-slots.update', $slot) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" name="name" value="{{ $slot->name }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">No.</label>
                                                    <input type="number" class="form-control" name="hour_number" value="{{ $slot->hour_number }}" min="1" max="20" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Tipo</label>
                                                    <select class="form-select" name="type" required>
                                                        <option value="class" {{ $slot->type == 'class' ? 'selected' : '' }}>Clase</option>
                                                        <option value="recess" {{ $slot->type == 'recess' ? 'selected' : '' }}>Receso</option>
                                                        <option value="lunch" {{ $slot->type == 'lunch' ? 'selected' : '' }}>Comida</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Hora Inicio</label>
                                                    <input type="time" class="form-control" name="start_time" value="{{ $slot->start_time }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Hora Fin</label>
                                                    <input type="time" class="form-control" name="end_time" value="{{ $slot->end_time }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Duración (min)</label>
                                                    <input type="number" class="form-control" name="duration_minutes" value="{{ $slot->duration_minutes }}" min="1" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No hay módulos configurados.
                                <a href="{{ route('schedules.time-slots.init') }}" class="btn btn-sm btn-primary">Inicializar Valores</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Resumen del Día</h5>
    </div>
    <div class="card-body">
        @php
        $classSlots = $slots->where('type', 'class');
        $totalMinutes = $classSlots->sum('duration_minutes');
        $totalHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;
        @endphp
        <div class="row text-center">
            <div class="col-md-3">
                <h3 class="text-primary">{{ $slots->where('type', 'class')->count() }}</h3>
                <p>Horas de Clase</p>
            </div>
            <div class="col-md-3">
                <h3 class="text-warning">{{ $slots->where('type', 'recess')->count() }}</h3>
                <p>Recesos</p>
            </div>
            <div class="col-md-3">
                <h3 class="text-info">{{ $slots->where('type', 'lunch')->count() }}</h3>
                <p>Comidas</p>
            </div>
            <div class="col-md-3">
                <h3 class="text-success">{{ $totalHours }}h {{ $remainingMinutes }}m</h3>
                <p>Tiempo Total de Clase</p>
            </div>
        </div>
    </div>
</div>
@endsection
