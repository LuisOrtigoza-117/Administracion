@extends('student.layout', ['student' => $user->student])

@section('title', 'Mi Perfil')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="fas fa-user-circle"></i> Mi Perfil</h2>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i> Información del Alumno</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lastname" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" value="{{ $user->student->lastname ?? '' }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->student->phone ?? '' }}" placeholder="ej. 55 1234 5678">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="student_number" class="form-label">Número de Estudiante</label>
                    <input type="text" class="form-control" id="student_number" name="student_number" value="{{ $user->student->student_number ?? '' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Grupo</label>
                    <input type="text" class="form-control" value="{{ $user->student->group->name ?? 'Sin grupo' }}" disabled>
                </div>
            </div>

            @if($user->student->group && $user->student->group->teacher)
            <div class="card bg-light mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i> Información del Docente</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nombre:</strong> {{ $user->student->group->teacher->name }}</p>
                            <p class="mb-0"><strong>Especialidad:</strong> {{ $user->student->group->teacher->specialty ?? 'No especificada' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Email:</strong> {{ $user->student->group->teacher->email }}</p>
                            @if($user->student->group->teacher->phone)
                            <p class="mb-0"><strong>Teléfono:</strong> {{ $user->student->group->teacher->phone }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="text-end">
                <button type="submit" class="btn btn-student">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-lock me-2"></i> Cambiar Contraseña</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('profile.password') }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="current_password" class="form-label">Contraseña Actual</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="new_password" class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" minlength="6" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="new_password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" minlength="6" required>
                </div>
            </div>
            
            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> Cambiar Contraseña
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
