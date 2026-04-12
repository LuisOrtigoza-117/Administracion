@extends('layouts.main')

@section('title', 'Nuevo Usuario')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="fas fa-user-plus"></i> Nuevo Usuario</h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label">Rol</label>
                    <select class="form-select" id="role" name="role" required onchange="toggleStudentFields()">
                        <option value="admin">Administrador</option>
                        <option value="teacher" selected>Profesor</option>
                        <option value="student">Estudiante</option>
                    </select>
                </div>
            </div>

            <div id="studentFields" style="display: none;">
                <hr>
                <h5>Datos del Estudiante</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="lastname" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="lastname" name="lastname">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="student_number" class="form-label">Número de Estudiante</label>
                        <input type="text" class="form-control" id="student_number" name="student_number">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="group_id" class="form-label">Grado</label>
                        <select class="form-select" id="grade" name="grade" onchange="filterGroupsByGrade()">
                            <option value="">Seleccionar grado...</option>
                            <option value="1">1er Grado</option>
                            <option value="2">2do Grado</option>
                            <option value="3">3er Grado</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="group_id" class="form-label">Grupo</label>
                        <select class="form-select" id="group_id" name="group_id">
                            <option value="">Seleccionar grupo...</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" data-grade="{{ $group->grade }}" style="display: none;">{{ $group->name }} - Sección {{ $group->section }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
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
