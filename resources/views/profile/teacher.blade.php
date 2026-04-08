@extends('layouts.main')

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

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h3>{{ $groups->count() }}</h3>
                <p class="text-muted mb-0">Grupos Asignados</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-user-graduate fa-3x text-success mb-3"></i>
                <h3>{{ $totalStudents }}</h3>
                <p class="text-muted mb-0">Total de Alumnos</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-clipboard-check fa-3x text-info mb-3"></i>
                <h3>{{ $groups->sum(fn($g) => $g->tasks->count()) }}</h3>
                <p class="text-muted mb-0">Tareas Creadas</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i> Mis Grupos Asignados</h5>
    </div>
    <div class="card-body">
        @if($groups->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Grupo</th>
                            <th>Grado</th>
                            <th>Sección</th>
                            <th>Ciclo Escolar</th>
                            <th>Alumnos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groups as $group)
                        <tr>
                            <td><strong>{{ $group->name }}</strong></td>
                            <td>{{ $group->grade }}</td>
                            <td>{{ $group->section }}</td>
                            <td>{{ $group->school_year }}</td>
                            <td><span class="badge bg-info">{{ $group->students->count() }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted mb-0">No tienes grupos asignados.</p>
        @endif
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-user me-2"></i> Información Personal</h5>
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
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->phone ?? '' }}" placeholder="ej. 55 1234 5678">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="specialty" class="form-label">Maestría/Carrera</label>
                    <input type="text" class="form-control" id="specialty" name="specialty" value="{{ $user->specialty ?? '' }}" placeholder="ej. Ingeniería en Sistemas">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Rol</label>
                    <input type="text" class="form-control" value="Profesor" disabled>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-warning text-dark">
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
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-key"></i> Cambiar Contraseña
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
