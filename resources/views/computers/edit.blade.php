@extends('layouts.main')

@section('title', 'Editar Equipo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit"></i> Editar Equipo</h1>
    <a href="{{ route('computers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('computers.update', $computer) }}" method="POST">
            @csrf
            @method('PUT')
            
            <h5 class="mb-3 text-primary"><i class="fas fa-info-circle"></i> Información Basic</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="pc_number" class="form-label">Número de PC *</label>
                    <input type="text" class="form-control" id="pc_number" name="pc_number" value="{{ $computer->pc_number }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="location" class="form-label">Ubicación *</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{ $computer->location }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Estado *</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="functional" {{ $computer->status == 'functional' ? 'selected' : '' }}>Funcional</option>
                        <option value="damaged" {{ $computer->status == 'damaged' ? 'selected' : '' }}>Dañado</option>
                        <option value="repairing" {{ $computer->status == 'repairing' ? 'selected' : '' }}>En Reparación</option>
                        <option value="retired" {{ $computer->status == 'retired' ? 'selected' : '' }}>Dado de Baja</option>
                    </select>
                </div>
            </div>

            <h5 class="mb-3 text-primary mt-4"><i class="fas fa-microchip"></i> Especificaciones del Equipo</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="brand" class="form-label">Marca</label>
                    <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand', $computer->brand) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="model" class="form-label">Modelo</label>
                    <input type="text" class="form-control" id="model" name="model" value="{{ old('model', $computer->model) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="processor" class="form-label">Procesador</label>
                    <input type="text" class="form-control" id="processor" name="processor" value="{{ old('processor', $computer->processor) }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="ram" class="form-label">Memoria RAM</label>
                    <input type="text" class="form-control" id="ram" name="ram" value="{{ old('ram', $computer->ram) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="storage" class="form-label">Almacenamiento</label>
                    <input type="text" class="form-control" id="storage" name="storage" value="{{ old('storage', $computer->storage) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="operating_system" class="form-label">Sistema Operativo</label>
                    <input type="text" class="form-control" id="operating_system" name="operating_system" value="{{ old('operating_system', $computer->operating_system) }}">
                </div>
            </div>

            <h5 class="mb-3 text-primary mt-4"><i class="fas fa-desktop"></i> Periféricos</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="monitor" class="form-label">Monitor</label>
                    <input type="text" class="form-control" id="monitor" name="monitor" value="{{ old('monitor', $computer->monitor) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="keyboard" class="form-label">Teclado</label>
                    <input type="text" class="form-control" id="keyboard" name="keyboard" value="{{ old('keyboard', $computer->keyboard) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="mouse" class="form-label">Mouse</label>
                    <input type="text" class="form-control" id="mouse" name="mouse" value="{{ old('mouse', $computer->mouse) }}">
                </div>
            </div>

            <h5 class="mb-3 text-primary mt-4"><i class="fas fa-calendar"></i> Información Adicional</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="purchase_date" class="form-label">Fecha de Compra</label>
                    <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ $computer->purchase_date ? $computer->purchase_date->format('Y-m-d') : '' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="notes" class="form-label">Notas</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $computer->notes) }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                <i class="fas fa-save"></i> Actualizar Equipo
            </button>
        </form>
    </div>
</div>
@endsection
