@extends('layouts.main')

@section('title', 'Tomar Asistencia')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-clipboard-check"></i> Tomar Asistencia</h1>
    <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-calendar"></i> Seleccionar Fecha</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('attendances.create') }}" id="dateForm">
                    <input type="hidden" name="group_id" value="{{ $groupId }}">
                    <div class="mb-3">
                        <label for="date" class="form-label">Fecha de Asistencia</label>
                        <input type="date" class="form-control form-control-lg" id="date" name="date" value="{{ $date }}">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-calendar-check"></i> Actualizar Fecha
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-users"></i> Seleccionar Grupo</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('attendances.create') }}" id="groupForm">
                    <input type="hidden" name="date" value="{{ $date }}">
                    <div class="mb-3">
                        <label for="group_id" class="form-label">Grupo</label>
                        <select class="form-select form-select-lg" id="group_id" name="group_id" required>
                            <option value="">-- Seleccionar Grupo --</option>
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ $groupId == $group->id ? 'selected' : '' }}>
                                {{ $group->name }} ({{ $group->students->count() }} estudiantes)
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-user-friends"></i> Cargar Estudiantes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@if($students->count() > 0)
<div class="card">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-users me-2"></i> 
            {{ $groups->find($groupId)->name ?? 'Grupo' }}
        </h4>
        <div>
            <span class="badge bg-light text-dark fs-6">{{ $students->count() }} estudiantes</span>
            <span class="badge bg-info fs-6">{{ $date }}</span>
        </div>
    </div>
    <div class="card-body p-0">
        <form action="{{ route('attendances.store') }}" method="POST" id="attendanceForm">
            @csrf
            <input type="hidden" name="group_id" value="{{ $groupId }}">
            <input type="hidden" name="date" value="{{ $date }}">
            
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px">#</th>
                            <th>Estudiante</th>
                            <th style="width: 200px">Estado</th>
                            <th style="width: 150px">Hora Llegada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $presentCount = 0; $absentCount = 0; @endphp
                        @foreach($students as $index => $student)
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-secondary rounded-circle" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-3">
                                        {{ substr($student->name, 0, 1) }}{{ substr($student->lastname ?? '', 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $student->name }} {{ $student->lastname }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-id-card"></i> {{ $student->student_number }}
                                        </small>
                                    </div>
                                </div>
                                <input type="hidden" name="attendances[{{ $index }}][student_id]" value="{{ $student->id }}">
                            </td>
                            <td>
                                <select class="form-select status-select" name="attendances[{{ $index }}][status]" required onchange="updateCount()">
                                    <option value="present">✅ Presente</option>
                                    <option value="absent">❌ Ausente</option>
                                    <option value="late">⏰ Retardo</option>
                                    <option value="justified">📝 Justificado</option>
                                </select>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                    <input type="time" class="form-control" name="attendances[{{ $index }}][arrival_time]" value="08:00">
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer bg-white border-top">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-success" onclick="markAll('present')">
                                ✅ Marcar Todos Presentes
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="markAll('absent')">
                                ❌ Marcar Todos Ausentes
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="d-inline-block me-3">
                            <span class="text-success"><i class="fas fa-check"></i> Presentes: <strong id="presentCount">0</strong></span>
                        </div>
                        <div class="d-inline-block me-3">
                            <span class="text-danger"><i class="fas fa-times"></i> Ausentes: <strong id="absentCount">0</strong></span>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i> Guardar Asistencia
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}
</style>

<script>
function markAll(status) {
    const selects = document.querySelectorAll('.status-select');
    selects.forEach(select => select.value = status);
    updateCount();
}

function updateCount() {
    const selects = document.querySelectorAll('.status-select');
    let present = 0, absent = 0;
    selects.forEach(select => {
        if (select.value === 'present' || select.value === 'late' || select.value === 'justified') present++;
        if (select.value === 'absent') absent++;
    });
    document.getElementById('presentCount').textContent = present;
    document.getElementById('absentCount').textContent = absent;
}

// Initialize count on page load
document.addEventListener('DOMContentLoaded', updateCount);
</script>

@elseif($groupId)
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    No hay estudiantes en el grupo seleccionado.
</div>
@else
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    Selecciona un grupo para tomar asistencia.
</div>
@endif
@endsection
