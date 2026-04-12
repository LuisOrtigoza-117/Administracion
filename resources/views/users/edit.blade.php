@extends('layouts.main')

@section('title', 'Editar Usuario')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="fas fa-user-edit"></i> Editar Usuario</h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Nueva Contraseña (dejar vacío para mantener)</label>
                    <input type="password" class="form-control" id="password" name="password" minlength="6">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label">Rol</label>
                    <select class="form-select" id="role" name="role" required onchange="toggleStudentFields()">
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>Profesor</option>
                        <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Estudiante</option>
                    </select>
                </div>
            </div>

            <div id="studentFields">
                <hr>
                <h5>Datos del Estudiante</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="lastname" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="{{ $student->lastname ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="student_number" class="form-label">Número de Estudiante</label>
                        <input type="text" class="form-control" id="student_number" name="student_number" value="{{ $student->student_number ?? '' }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="grade" class="form-label">Grado</label>
                        <select class="form-select" id="grade" name="grade" onchange="filterGroupsByGrade()">
                            <option value="">Seleccionar grado...</option>
                            <option value="1" {{ ($student->group->grade ?? null) == '1' ? 'selected' : '' }}>1er Grado</option>
                            <option value="2" {{ ($student->group->grade ?? null) == '2' ? 'selected' : '' }}>2do Grado</option>
                            <option value="3" {{ ($student->group->grade ?? null) == '3' ? 'selected' : '' }}>3er Grado</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="group_id" class="form-label">Grupo</label>
                        <select class="form-select" id="group_id" name="group_id">
                            <option value="">Seleccionar grupo...</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" data-grade="{{ $group->grade }}" {{ ($student->group_id ?? null) == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }} - Sección {{ $group->section }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <script>
            // Initialize visibility on page load
            document.addEventListener('DOMContentLoaded', function() {
                toggleStudentFields();
            });
            </script>

            <div class="text-end">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleStudentFields() {
    const role = document.getElementById('role').value;
    const studentFields = document.getElementById('studentFields');
    if (role === 'student') {
        studentFields.style.display = 'block';
    } else {
        studentFields.style.display = 'none';
    }
}

function filterGroupsByGrade() {
    const grade = document.getElementById('grade').value;
    const groupSelect = document.getElementById('group_id');
    const options = groupSelect.querySelectorAll('option');
    
    // Reset group selection
    groupSelect.value = '';
    
    options.forEach(option => {
        if (option.value === '') return;
        if (grade === '' || option.getAttribute('data-grade') === grade) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    });
}
</script>
@endsection
