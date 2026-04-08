@extends('layouts.main')

@section('title', 'Nuevo Estudiante')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-user-plus"></i> Nuevo Estudiante</h1>
    <a href="{{ route('students.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nombre(s) *</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lastname" class="form-label">Apellidos *</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                    @error('lastname') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="student_number" class="form-label">Número de Control *</label>
                    <input type="text" class="form-control" id="student_number" name="student_number" value="{{ old('student_number') }}" required>
                    @error('student_number') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="phone" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="ej. 55 1234 5678">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="grade" class="form-label">Grado *</label>
                    <select class="form-select" id="grade" name="grade" required onchange="filterGroupsByGrade()">
                        <option value="">-- Seleccionar Grado --</option>
                        <option value="1" {{ ($grade ?? '') == '1' ? 'selected' : '' }}>1° Año</option>
                        <option value="2" {{ ($grade ?? '') == '2' ? 'selected' : '' }}>2° Año</option>
                        <option value="3" {{ ($grade ?? '') == '3' ? 'selected' : '' }}>3° Año</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="group_id" class="form-label">Grupo *</label>
                    <select class="form-select" id="group_id" name="group_id" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach($groups as $group)
                        <option value="{{ $group->id }}" data-grade="{{ $group->grade }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                            {{ $group->name }} - Sección: {{ $group->section }}
                        </option>
                        @endforeach
                    </select>
                    @error('group_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Estudiante
            </button>
        </form>
    </div>
</div>

<script>
function filterGroupsByGrade() {
    const grade = document.getElementById('grade').value;
    const groupSelect = document.getElementById('group_id');
    const options = groupSelect.querySelectorAll('option');
    
    options.forEach(function(option) {
        if (option.value === '') return;
        const optionGrade = option.getAttribute('data-grade');
        if (!grade || optionGrade === grade) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    });
    
    if (groupSelect.value) {
        const selectedOption = groupSelect.querySelector('option[value="' + groupSelect.value + '"]');
        if (selectedOption && selectedOption.style.display === 'none') {
            groupSelect.value = '';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    filterGroupsByGrade();
});
</script>
@endsection
