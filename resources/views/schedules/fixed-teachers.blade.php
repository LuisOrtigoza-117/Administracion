@extends('layouts.main')

@section('title', 'Horarios Fijos de Maestros')

@section('content')
<style>
@media print {
    .btn, nav, form, .no-print { display: none !important; }
    body { background: white; }
    .card { box-shadow: none; border: 1px solid #ddd; }
    table { font-size: 10px; }
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-lock"></i> Horarios Fijos de Maestros</h1>
    <div>
        <a href="{{ route('schedules.time-slots') }}" class="btn btn-info">
            <i class="fas fa-clock"></i> Módulos de Tiempo
        </a>
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>Importante:</strong> Los horarios fijos son para maestros que NO pueden cambiar de grupo/hora. 
    Estos horarios tienen prioridad y no pueden ser modificados automáticamente.
</div>

@php
if(isset($timeSlots) && $timeSlots->isEmpty()) {
    \App\Models\TimeSlot::initializeDefaults();
    $timeSlots = \App\Models\TimeSlot::getActiveSlots();
}
@endphp

<div class="card mb-4 no-print">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0"><i class="fas fa-plus"></i> Agregar Horario Fijo</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('schedules.fixed-teachers.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="teacher_id" class="form-label">Maestro *</label>
                    <select class="form-select" name="teacher_id" required>
                        <option value="">Seleccionar...</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="day" class="form-label">Día *</label>
                    <select class="form-select" name="day" required>
                        <option value="monday">Lunes</option>
                        <option value="tuesday">Martes</option>
                        <option value="wednesday">Miércoles</option>
                        <option value="thursday">Jueves</option>
                        <option value="friday">Viernes</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="hour_number" class="form-label">Hora *</label>
                    <select class="form-select" name="hour_number" required onchange="updateFixedTimes()">
                        <option value="">Seleccionar...</option>
                        @foreach(isset($timeSlots) ? $timeSlots->where('type', 'class') : [] as $slot)
                            <option value="{{ $slot->hour_number }}" data-start="{{ $slot->start_time }}" data-end="{{ $slot->end_time }}">
                                {{ $slot->name }} ({{ $slot->start_time }} - {{ $slot->end_time }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="start_time" class="form-label">Inicio</label>
                    <input type="time" class="form-control" name="start_time" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="end_time" class="form-label">Fin</label>
                    <input type="time" class="form-control" name="end_time" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="subject" class="form-label">Materia *</label>
                    <input type="text" class="form-control" name="subject" required placeholder="Ej: Matemáticas">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="grade_group" class="form-label">Grado y Grupo Fijo *</label>
                    <input type="text" class="form-control" name="grade_group" required placeholder="Ej: 1°A, 2°C">
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-lock"></i> Crear Horario Fijo
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@php
$days = ['monday' => 'Lunes', 'tuesday' => 'Martes', 'wednesday' => 'Miércoles', 'thursday' => 'Jueves', 'friday' => 'Viernes'];
$groupedFixed = $fixedSchedules->groupBy('teacher_id');
@endphp

<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Horarios Fijos Registrados</h5>
    </div>
    <div class="card-body">
        @if($fixedSchedules->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Maestro</th>
                            <th>Día</th>
                            <th>Hora</th>
                            <th>Horario</th>
                            <th>Materia</th>
                            <th>Grado/Grupo Fijo</th>
                            <th class="no-print">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fixedSchedules as $fs)
                            <tr class="table-warning">
                                <td>{{ $fs->teacher->name ?? 'N/A' }}</td>
                                <td>{{ $days[$fs->day] ?? $fs->day }}</td>
                                <td>{{ $fs->start_time }} - {{ $fs->end_time }}</td>
                                <td><span class="badge bg-warning text-dark">{{ $fs->hour_number }}ª Hora</span></td>
                                <td>{{ $fs->subject }}</td>
                                <td><strong>{{ $fs->grade_group }}</strong></td>
                                <td class="no-print">
                                    <form action="{{ route('schedules.fixed-teachers.destroy', $fs) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este horario fijo?')">
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
            <p class="text-center text-muted">No hay horarios fijos registrados</p>
        @endif
    </div>
</div>

<script>
function updateFixedTimes() {
    const select = document.querySelector('select[name="hour_number"]');
    const option = select.options[select.selectedIndex];
    if (option.value) {
        document.querySelector('input[name="start_time"]').value = option.dataset.start;
        document.querySelector('input[name="end_time"]').value = option.dataset.end;
    }
}
</script>
@endsection
