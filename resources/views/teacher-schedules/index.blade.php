@extends('layouts.main')

@section('title', 'Mi Horario')

@section('content')
<style>
@media print {
    .btn, .no-print { display: none !important; }
    body { background: white; }
    .card { box-shadow: none; border: 1px solid #ddd; page-break-inside: avoid; }
    table { font-size: 10px; }
    th, td { padding: 4px; }
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <h1><i class="fas fa-calendar-alt"></i> Mi Horario de Clases</h1>
    <div>
        <a href="{{ route('teacher-schedules.print') }}" class="btn btn-secondary" target="_blank">
            <i class="fas fa-print"></i> Imprimir Mi Horario
        </a>
    </div>
</div>

@if($schedules->count() > 0)
    @php
    $allHours = $schedules->pluck('hour_number')->unique()->sort()->values();
    @endphp
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-calendar-week"></i> Horario Semanal
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th style="width: 80px;">Hora</th>
                            @foreach($days as $dayKey => $dayName)
                                <th>{{ $dayName }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allHours as $hour)
                            @php
                            $hourSchedules = $schedules->where('hour_number', $hour);
                            $firstSchedule = $hourSchedules->first();
                            @endphp
                            <tr class="{{ $firstSchedule && $firstSchedule->is_recess ? 'table-warning' : '' }}">
                                <td class="text-center {{ $firstSchedule && $firstSchedule->is_recess ? 'bg-warning' : 'bg-light' }}">
                                    <strong>{{ $hour }}ª</strong><br>
                                    <small>{{ $firstSchedule ? $firstSchedule->start_time . ' - ' . $firstSchedule->end_time : '' }}</small>
                                </td>
                                @foreach($days as $dayKey => $dayName)
                                    @php
                                    $daySchedule = $schedules->where('day', $dayKey)->where('hour_number', $hour)->first();
                                    @endphp
                                    <td class="text-center {{ $daySchedule && $daySchedule->is_recess ? 'table-warning' : '' }}" style="min-width: 100px;">
                                        @if($daySchedule)
                                            @if($daySchedule->is_recess)
                                                <span class="badge bg-warning text-dark">RECESO</span>
                                            @else
                                                <strong>{{ $daySchedule->subject }}</strong>
                                                <br>
                                                <small class="text-primary">
                                                    <i class="fas fa-users"></i> {{ $daySchedule->group->name ?? 'Grupo' }} ({{ $daySchedule->group->grade ?? '' }}°)
                                                </small>
                                            @endif
                                            <div class="mt-1 no-print">
                                                <a href="{{ route('schedules.edit', $daySchedule) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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
    
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Resumen de Clases</h5>
        </div>
        <div class="card-body">
            @php
            $totalClasses = $schedules->where('is_recess', false)->count();
            $groupCounts = $schedules->where('is_recess', false)->groupBy('group_id')->map->count();
            @endphp
            
            <div class="row text-center">
                <div class="col-md-4">
                    <h3 class="text-primary">{{ $totalClasses }}</h3>
                    <p>Total de Clases</p>
                </div>
                <div class="col-md-4">
                    <h3 class="text-success">{{ $groups->count() }}</h3>
                    <p>Grupos Asignados</p>
                </div>
                <div class="col-md-4">
                    <h3 class="text-info">{{ $totalClasses > 0 ? round($totalClasses / 5, 1) : 0 }}</h3>
                    <p>Clases por Día (promedio)</p>
                </div>
            </div>
            
            <hr>
            
            <h6>Clases por Grupo:</h6>
            <ul class="list-group">
                @foreach($groupCounts as $groupId => $count)
                    @php $group = $groups->find($groupId); @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $group->name ?? 'Grupo ' . $groupId }} ({{ $group->grade ?? 'N/A' }}° Año)
                        <span class="badge bg-primary rounded-pill">{{ $count }} clases</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center">
            <i class="fas fa-calendar-times text-muted" style="font-size: 48px;"></i>
            <h4 class="mt-3 text-muted">No tienes clases asignadas</h4>
            <p>Contacta al administrador para que te asigne grupos y materias.</p>
        </div>
    </div>
@endif
@endsection
