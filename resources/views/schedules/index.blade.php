@extends('layouts.main')

@section('title', 'Horarios')

@section('content')
<style>
@media print {
    .btn, .no-print, .card-body form { display: none !important; }
    body { background: white; }
    .card { box-shadow: none; border: 1px solid #ddd; page-break-inside: avoid; }
    table { font-size: 10px; }
    th, td { padding: 4px; }
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-calendar-alt"></i> Horarios</h1>
    <div>
        <a href="{{ route('schedules.print', ['grade' => request('grade')]) }}" class="btn btn-secondary" target="_blank">
            <i class="fas fa-print"></i> Imprimir
        </a>
        <a href="{{ route('schedules.fixed-teachers') }}" class="btn btn-warning">
            <i class="fas fa-lock"></i> Horarios Fijos
        </a>
        <a href="{{ route('schedules.create', ['group_id' => request('group_id')]) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Agregar Hora
        </a>
    </div>
</div>

<div class="card mb-4 no-print">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="grade" class="form-label">Grado</label>
                <select class="form-select" id="grade" name="grade" onchange="this.form.submit()">
                    <option value="">Todos los grados</option>
                    <option value="1" {{ request('grade') == '1' ? 'selected' : '' }}>1° Año</option>
                    <option value="2" {{ request('grade') == '2' ? 'selected' : '' }}>2° Año</option>
                    <option value="3" {{ request('grade') == '3' ? 'selected' : '' }}>3° Año</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="group_id" class="form-label">Grupo</label>
                <select class="form-select" id="group_id" name="group_id" onchange="this.form.submit()">
                    <option value="">Todos los grupos</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                            {{ $group->grade }}° - {{ $group->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

@php
$days = ['monday' => 'Lunes', 'tuesday' => 'Martes', 'wednesday' => 'Miércoles', 'thursday' => 'Jueves', 'friday' => 'Viernes'];
@endphp

@forelse($groups as $group)
    @if(!request('group_id') || request('group_id') == $group->id)
        @if(!request('grade') || request('grade') == $group->grade)
            @php
            $groupSchedules = $schedules->where('group_id', $group->id);
            $hasSchedules = $groupSchedules->count() > 0;
            $hours = $groupSchedules->pluck('hour_number')->unique()->sort()->values();
            @endphp
            
            @if($hasSchedules)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $group->grade }}° Año - Grupo {{ $group->name }}</h5>
                    <a href="{{ route('schedules.create', ['group_id' => $group->id]) }}" class="btn btn-sm btn-light">
                        <i class="fas fa-plus"></i> Agregar
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 80px;">Hora</th>
                                    @foreach($days as $day)
                                        <th>{{ $day }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hours as $hour)
                                    @php
                                    $hourSchedules = $groupSchedules->where('hour_number', $hour);
                                    $firstSchedule = $hourSchedules->first();
                                    @endphp
                                    <tr class="{{ $firstSchedule && $firstSchedule->is_recess ? 'table-warning' : '' }}">
                                        <td class="text-center {{ $firstSchedule && $firstSchedule->is_recess ? 'bg-warning' : 'bg-light' }}">
                                            <strong>{{ $hour }}ª</strong><br>
                                            <small class="text-muted">{{ $firstSchedule ? $firstSchedule->start_time . ' - ' . $firstSchedule->end_time : '' }}</small>
                                        </td>
                                        @foreach($days as $dayKey => $dayName)
                                            @php
                                            $dayItem = $groupSchedules->where('day', $dayKey)->where('hour_number', $hour)->first();
                                            @endphp
                                            <td style="min-width: 120px; vertical-align: top;" class="{{ $dayItem && $dayItem->is_recess ? 'table-warning' : '' }}">
                                                @if($dayItem)
                                                    @if($dayItem->is_recess)
                                                        <span class="badge bg-warning text-dark">RECESO</span>
                                                        <br><small>{{ $dayItem->start_time }} - {{ $dayItem->end_time }}</small>
                                                    @else
                                                        <div class="mb-1">
                                                            <strong>{{ $dayItem->subject }}</strong>
                                                        </div>
                                                        <small class="text-muted">
                                                            {{ $dayItem->teacher ? $dayItem->teacher->name : 'Sin asignar' }}
                                                        </small>
                                                    @endif
                                                    <div class="mt-1 no-print">
                                                        <a href="{{ route('schedules.edit', $dayItem) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('schedules.destroy', $dayItem) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $group->grade }}° Año - Grupo {{ $group->name }}</h5>
                    <a href="{{ route('schedules.create', ['group_id' => $group->id]) }}" class="btn btn-sm btn-light">
                        <i class="fas fa-plus"></i> Agregar
                    </a>
                </div>
                <div class="card-body text-center text-muted">
                    <p>No hay horas registradas para este grupo</p>
                    <a href="{{ route('schedules.create', ['group_id' => $group->id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Agregar primera hora
                    </a>
                </div>
            </div>
            @endif
        @endif
    @endif
@empty
<div class="card">
    <div class="card-body">
        <p class="text-center text-muted">No hay grupos registrados</p>
    </div>
</div>
@endforelse

<div class="card mt-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="fas fa-lock"></i> Horarios Fijos de Maestros</h5>
    </div>
    <div class="card-body">
        @if($fixedSchedules->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Maestro</th>
                            <th>Día</th>
                            <th>Hora</th>
                            <th>Materia</th>
                            <th>Grado/Grupo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fixedSchedules as $fs)
                            <tr>
                                <td>{{ $fs->teacher->name ?? 'N/A' }}</td>
                                <td>{{ $days[$fs->day] ?? $fs->day }}</td>
                                <td>{{ $fs->start_time }} - {{ $fs->end_time }}</td>
                                <td>{{ $fs->subject }}</td>
                                <td>{{ $fs->grade_group }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-muted mb-0">No hay horarios fijos registrados</p>
        @endif
    </div>
</div>
@endsection
