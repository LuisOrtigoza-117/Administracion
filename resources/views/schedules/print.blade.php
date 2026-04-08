@extends('layouts.main')

@section('title', 'Imprimir Horarios')

@section('content')
<style>
@media print {
    body { background: white; }
    .no-print { display: none !important; }
    .card { box-shadow: none; border: 1px solid #ddd; margin-bottom: 20px; page-break-after: always; }
    table { font-size: 9px; }
    th, td { padding: 3px; }
    h5 { font-size: 12px; }
}
</style>

<div class="card mb-4 no-print">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="fas fa-filter"></i> Seleccionar Horarios para Imprimir</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="grade" class="form-label">Grado</label>
                <select class="form-select" id="grade" name="grade" onchange="this.form.submit()">
                    <option value="">Todos los grados</option>
                    <option value="1" {{ request('grade') == '1' ? 'selected' : '' }}>1° Año</option>
                    <option value="2" {{ request('grade') == '2' ? 'selected' : '' }}>2° Año</option>
                    <option value="3" {{ request('grade') == '3' ? 'selected' : '' }}>3° Año</option>
                </select>
            </div>
            <div class="col-md-5">
                <label for="group_ids" class="form-label">Grupos</label>
                <select class="form-select" id="group_ids" name="group_ids[]" multiple size="5">
                    @php
                    $allGroups = \App\Models\Group::orderBy('grade')->orderBy('name')->get();
                    @endphp
                    @foreach($allGroups as $g)
                        <option value="{{ $g->id }}" {{ in_array($g->id, $selectedGroupIds ?? []) ? 'selected' : '' }}>
                            {{ $g->grade }}° - {{ $g->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Mantén Ctrl/Cmd presionado para seleccionar varios</small>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                <a href="{{ route('schedules.print') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<div class="no-print mb-4">
    <button onclick="window.print()" class="btn btn-primary">
        <i class="fas fa-print"></i> Imprimir Horarios
    </button>
    <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="text-center mb-4">
    <h2>ESCUELA SECUNDARIA TECNICA No.53</h2>
    <h4>HORARIO DE CLASES</h4>
    @if($grade)
        <h5>{{ $grade }}° Año</h5>
    @endif
    @if(count($selectedGroupIds) > 0)
        <h6 class="text-muted">{{ count($selectedGroupIds) }} grupo(s) seleccionado(s)</h6>
    @endif
    <hr>
</div>

@php
$days = ['monday' => 'LUNES', 'tuesday' => 'MARTES', 'wednesday' => 'MIERCOLES', 'thursday' => 'JUEVES', 'friday' => 'VIERNES'];
$selectedGroupIds = $selectedGroupIds ?? request()->get('group_ids', []);
$printGroups = $groups;
if (!empty($selectedGroupIds)) {
    $printGroups = $groups->whereIn('id', $selectedGroupIds);
}
@endphp

@forelse($printGroups as $group)
    @php
    $groupSchedules = $schedules->where('group_id', $group->id);
    @endphp
    
    @if($groupSchedules->count() > 0)
    @php
    $hours = $groupSchedules->pluck('hour_number')->unique()->sort()->values();
    @endphp
    <div class="card mb-3">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">{{ $group->grade }}° AÑO - GRUPO {{ $group->name }}</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 50px;">HORA</th>
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
                                <small>{{ $firstSchedule ? $firstSchedule->start_time . ' - ' . $firstSchedule->end_time : '' }}</small>
                            </td>
                            @foreach($days as $dayKey => $dayName)
                                @php
                                $daySchedule = $groupSchedules->where('day', $dayKey)->where('hour_number', $hour)->first();
                                @endphp
                                <td class="text-center {{ $daySchedule && $daySchedule->is_recess ? 'bg-warning' : '' }}" style="min-width: 70px;">
                                    @if($daySchedule)
                                        @if($daySchedule->is_recess)
                                            <strong>RECESO</strong><br>
                                            <small>{{ $daySchedule->start_time }} - {{ $daySchedule->end_time }}</small>
                                        @else
                                            <strong>{{ $daySchedule->subject }}</strong>
                                            <br><small>{{ $daySchedule->teacher ? $daySchedule->teacher->name : '-' }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@empty
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No hay horarios para mostrar con los filtros seleccionados.
    </div>
@endforelse

<div class="no-print mt-4 text-center text-muted">
    <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    <p>ESCUELA SECUNDARIA TECNICA No.53</p>
</div>
@endsection
