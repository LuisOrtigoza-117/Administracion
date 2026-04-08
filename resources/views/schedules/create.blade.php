@extends('layouts.main')

@section('title', 'Agregar Hora')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-plus"></i> Agregar Hora al Horario</h1>
    <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card mb-3">
    <div class="card-header bg-info text-white">
        <i class="fas fa-magic"></i> Automatización
    </div>
    <div class="card-body">
        <p class="mb-0">
            <strong>Auto-detectar:</strong> Selecciona el número de hora y los tiempos se auto-completarán según la configuración.<br>
            <strong>Receso rápido:</strong> Si la hora corresponde a un receso, se detectará automáticamente.
        </p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('schedules.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="group_id" class="form-label">Grupo *</label>
                    <select class="form-select" id="group_id" name="group_id" required>
                        <option value="">Seleccionar grupo...</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ ($groupId == $group->id || old('group_id')) == $group->id ? 'selected' : '' }}>
                                {{ $group->grade }}° - {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="day" class="form-label">Día *</label>
                    <select class="form-select" id="day" name="day" required>
                        <option value="">Seleccionar...</option>
                        <option value="monday" {{ old('day') == 'monday' ? 'selected' : '' }}>Lunes</option>
                        <option value="tuesday" {{ old('day') == 'tuesday' ? 'selected' : '' }}>Martes</option>
                        <option value="wednesday" {{ old('day') == 'wednesday' ? 'selected' : '' }}>Miércoles</option>
                        <option value="thursday" {{ old('day') == 'thursday' ? 'selected' : '' }}>Jueves</option>
                        <option value="friday" {{ old('day') == 'friday' ? 'selected' : '' }}>Viernes</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="hour_number" class="form-label">Número de Hora *</label>
                    <select class="form-select" id="hour_number" name="hour_number" required onchange="autoFillTime()">
                        <option value="">Seleccionar hora...</option>
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot->hour_number }}" 
                                    data-start="{{ $slot->start_time }}"
                                    data-end="{{ $slot->end_time }}"
                                    data-type="{{ $slot->type }}"
                                    {{ old('hour_number') == $slot->hour_number ? 'selected' : '' }}>
                                {{ $slot->hour_number }}ª Hora ({{ $slot->start_time }} - {{ $slot->end_time }})
                                @if($slot->type === 'recess') - RECESO @endif
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Los tiempos se auto-completan</small>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="start_time" class="form-label">Hora de Inicio *</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" 
                           value="{{ old('start_time', '07:00') }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="end_time" class="form-label">Hora de Fin *</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" 
                           value="{{ old('end_time', '07:50') }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="is_recess" class="form-label">Tipo</label>
                    <select class="form-select" id="is_recess" name="is_recess" onchange="toggleRecessMode()">
                        <option value="">Clase</option>
                        <option value="1">Receso (1 día)</option>
                        <option value="all">Receso (Todos los días)</option>
                    </select>
                </div>
            </div>

            <div id="class-fields">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="subject" class="form-label">Materia *</label>
                        <input type="text" class="form-control" id="subject" name="subject" 
                               value="{{ old('subject') }}"
                               placeholder="Ej: Matemáticas, Español, Ciencias...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="teacher_id" class="form-label">Maestro</label>
                        <select class="form-select" id="teacher_id" name="teacher_id">
                            <option value="">Sin asignar</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
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
                <i class="fas fa-save"></i> Guardar Horario
            </button>
        </form>
    </div>
</div>

<script>
function toggleRecessMode() {
    const isRecess = document.getElementById('is_recess').value === '1';
    const isRecessAll = document.getElementById('is_recess').value === 'all';
    const classFields = document.getElementById('class-fields');
    const subjectInput = document.getElementById('subject');
    const submitBtn = document.getElementById('submit-btn');
    
    if (isRecess || isRecessAll) {
        classFields.style.display = 'none';
        subjectInput.removeAttribute('required');
        submitBtn.innerHTML = '<i class="fas fa-coffee"></i> Guardar Receso';
        submitBtn.className = 'btn btn-warning';
    } else {
        classFields.style.display = 'block';
        subjectInput.setAttribute('required', 'required');
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Clase';
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
            isRecess.value = 'all';
            toggleRecessMode();
        } else {
            isRecess.value = '';
            toggleRecessMode();
        }
    }
}

// Auto-fill on page load if hour is pre-selected
document.addEventListener('DOMContentLoaded', function() {
    const hourSelect = document.getElementById('hour_number');
    if (hourSelect.value) {
        autoFillTime();
    }
});
</script>
@endsection
