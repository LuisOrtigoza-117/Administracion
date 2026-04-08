@extends('layouts.main')

@section('title', 'Editar Grupo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit"></i> Editar Grupo</h1>
    <a href="{{ route('groups.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('groups.update', $group) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nombre del Grupo</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $group->name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="grade" class="form-label">Grado</label>
                    <input type="text" class="form-control" id="grade" name="grade" value="{{ $group->grade }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="section" class="form-label">Sección</label>
                    <input type="text" class="form-control" id="section" name="section" value="{{ $group->section }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="school_year" class="form-label">Ciclo Escolar</label>
                    <input type="text" class="form-control" id="school_year" name="school_year" value="{{ $group->school_year }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="teacher_id" class="form-label">Docente Encargado</label>
                    <select class="form-select" id="teacher_id" name="teacher_id">
                        <option value="">Seleccionar docente...</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $group->teacher_id == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar
            </button>
        </form>
    </div>
</div>
@endsection
