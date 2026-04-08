@extends('layouts.main')

@section('title', 'Editar Hora')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit"></i> Editar Hora del Horario</h1>
    <a href="{{ route('schedules.index', ['group_id' => $schedule->group_id]) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('schedules.update', $schedule) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="group_id" class="form-label">Grado y Grupo *</label>
                    <select class="form-select" id="group_id" name="group_id" required>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ $schedule->group_id == $group->id ? 'selected' : '' }}>
                                {{ $group->grade }}° - {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="day" class="form-label">Día *</label>
                    <select class="form-select" id="day" name="day" required>
                        <option value="monday" {{ $schedule->day == 'monday' ? 'selected' : '' }}>Lunes</option>
                        <option value="tuesday" {{ $schedule->day == 'tuesday' ? 'selected' : '' }}>Martes</option>
                        <option value="wednesday" {{ $schedule->day == 'wednesday' ? 'selected' : '' }}>Miércoles</option>
                        <option value="thursday" {{ $schedule->day == 'thursday' ? 'selected' : '' }}>Jueves</option>
                        <option value="friday" {{ $schedule->day == 'friday' ? 'selected' : '' }}>Viernes</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="hour_number" class="form-label">Hora *</label>
                    <select class="form-select" id="hour_number" name="hour_number" required onchange="autoFillTime()">
                        <option value="">Seleccionar hora...</option>
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot->hour_number }}" 
                                    data-start="{{ $slot->start_time }}"
                                    data-end="{{ $slot->end_time }}"
                                    data-type="{{ $slot->type }}"
                                    {{ $schedule->hour_number == $slot->hour_number ? 'selected' : '' }}>
                                {{ $slot->hour_number }}ª Hora ({{ $slot->start_time }} - {{ $slot->end_time }})
                                @if($slot->type === 'recess') - RECESO @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="start_time" class="form-label">Hora de Inicio *</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" value="{{ $schedule->start_time }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="end_time" class="form-label">Hora de Fin *</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" value="{{ $schedule->end_time }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="is_recess" class="form-label">Tipo</label>
                    <select class="form-select" id="is_recess" name="is_recess" onchange="toggleRecessMode()">
                        <option value="0" {{ !$schedule->is_recess ? 'selected' : '' }}>Clase</option>
                        <option value="1" {{ $schedule->is_recess ? 'selected' : '' }}>Receso</option>
                    </select>
                </div>
            </div>

            <div id="class-fields">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="subject" class="form-label">Materia *</label>
                        <input type="text" class="form-control" id="subject" name="subject" value="{{ $schedule->subject }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="teacher_id" class="form-label">Maestro</label>
                        <select class="form-select" id="teacher_id" name="teacher_id">
                            <option value="">Sin asignar</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $schedule->teacher_id == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <hr>
            <h5>Maestros con Horarios Fijos</h5>
            @if($fixedTeachers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Maestro</th>
                                <th>Día</th>
                                <th>Hora</th>
                                <th>Materia</th>
                                <th>Grupo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fixedTeachers as $ft)
                                <tr class="table-warning">
                                    <td>{{ $ft->teacher->name ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($ft->day) }}</td>
                                    <td>{{ $ft->start_time }} - {{ $ft->end_time }}</td>
                                    <td>{{ $ft->subject }}</td>
                                    <td>{{ $ft->grade_group }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No hay horarios fijos registrados</p>
            @endif

            <button type="submit" class="btn btn-primary" id="submit-btn">
                <i class="fas fa-save"></i> Actualizar Horario
            </button>
        </form>
    </div>
</div>

<script>
function toggleRecessMode() {
    const isRecess = document.getElementById('is_recess').value === '1';
    const classFields = document.getElementById('class-fields');
    const subjectInput = document.getElementById('subject');
    const submitBtn = document.getElementById('submit-btn');
    
    if (isRecess) {
        classFields.style.display = 'none';
        subjectInput.removeAttribute('required');
        submitBtn.innerHTML = '<i class="fas fa-coffee"></i> Actualizar Receso';
        submitBtn.className = 'btn btn-warning';
    } else {
        classFields.style.display = 'block';
        subjectInput.setAttribute('required', 'required');
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Actualizar Horario';
        submitBtn.className = 'btn btn-primary';
    }
}

function autoFillTime() {
    const hourSelect = document.getElementById('hour_number');
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    const isRecess = document.getElementById('is_recess');
    
    const selectedOption = hourSelect.options[hourSelect.selectedIndex];
    if (selectedOption && selectedOption.value) {
        startTime.value = selectedOption.dataset.start || '07:00';
        endTime.value = selectedOption.dataset.end || '07:50';
        
        const type = selectedOption.dataset.type;
        if (type === 'recess') {
            isRecess.value = '1';
            toggleRecessMode();
        } else {
            isRecess.value = '0';
            toggleRecessMode();
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleRecessMode();
});
</script>
@endsection
