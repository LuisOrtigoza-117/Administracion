@extends('layouts.main')

@section('title', 'Imprimir Mi Horario')

@section('content')
<style>
@media print {
    body { background: white; }
    .no-print { display: none !important; }
    .card { box-shadow: none; border: 1px solid #ddd; margin-bottom: 20px; }
    table { font-size: 10px; }
    th, td { padding: 4px; }
}
</style>

<div class="no-print mb-4">
    <button onclick="window.print()" class="btn btn-primary">
        <i class="fas fa-print"></i> Imprimir
    </button>
    <a href="{{ route('teacher-schedules.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="text-center mb-4">
    <h2>ESCUELA SECUNDARIA TECNICA No.53</h2>
    <h4>HORARIO DEL PROFESOR</h4>
    <h5>{{ $user->name }}</h5>
    <hr>
</div>

@php
$days = ['monday' => 'LUNES', 'tuesday' => 'MARTES', 'wednesday' => 'MIÉRCOLES', 'thursday' => 'JUEVES', 'friday' => 'VIERNES'];
$allHours = $schedules->pluck('hour_number')->unique()->sort()->values();
@endphp

<div class="card mb-3">
    <div class="card-header bg-primary text-white py-2">
        <h5 class="mb-0">HORARIO SEMANAL</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-sm mb-0">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width: 80px;">HORA</th>
                    @foreach($days as $dayName)
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
                            <td class="text-center {{ $daySchedule && $daySchedule->is_recess ? 'bg-warning' : '' }}" style="min-width: 100px;">
                                @if($daySchedule)
                                    @if($daySchedule->is_recess)
                                        <strong>RECESO</strong>
                                    @else
                                        <strong>{{ $daySchedule->subject }}</strong>
                                        <br>
                                        <small class="text-primary">
                                            <i class="fas fa-users"></i> {{ $daySchedule->group->name ?? 'Grupo' }} ({{ $daySchedule->group->grade ?? '' }}°)
                                        </small>
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

<div class="no-print mt-4 text-center text-muted">
    <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    <p>ESCUELA SECUNDARIA TECNICA No.53</p>
</div>
@endsection
