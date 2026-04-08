@extends('layouts.main')

@section('title', 'Nuevo Equipo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-desktop"></i> Nuevo Equipo</h1>
    <a href="{{ route('computers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('computers.store') }}" method="POST">
            @csrf
            <h5 class="mb-3 text-primary"><i class="fas fa-info-circle"></i> Información Basic</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="pc_number" class="form-label">Número de PC *</label>
                    <input type="text" class="form-control" id="pc_number" name="pc_number" placeholder="ej. PC-01" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="location" class="form-label">Ubicación *</label>
                    <input type="text" class="form-control" id="location" name="location" placeholder="ej. Lab 1, Fila 1" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Estado *</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="functional">Funcional</option>
                        <option value="damaged">Dañado</option>
                        <option value="repairing">En Reparación</option>
                        <option value="retired">Dado de Baja</option>
                    </select>
                </div>
            </div>

            <h5 class="mb-3 text-primary mt-4"><i class="fas fa-microchip"></i> Especificaciones del Equipo</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="brand" class="form-label">Marca</label>
                    <input type="text" class="form-control" id="brand" name="brand" placeholder="ej. Dell, HP, Lenovo">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="model" class="form-label">Modelo</label>
                    <input type="text" class="form-control" id="model" name="model" placeholder="ej. Optiplex 7080">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="processor" class="form-label">Procesador</label>
                    <input type="text" class="form-control" id="processor" name="processor" placeholder="ej. Intel Core i5">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="ram" class="form-label">Memoria RAM</label>
                    <input type="text" class="form-control" id="ram" name="ram" placeholder="ej. 8GB DDR4">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="storage" class="form-label">Almacenamiento</label>
                    <input type="text" class="form-control" id="storage" name="storage" placeholder="ej. 256GB SSD">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="operating_system" class="form-label">Sistema Operativo</label>
                    <input type="text" class="form-control" id="operating_system" name="operating_system" placeholder="ej. Windows 10 Pro">
                </div>
            </div>

            <h5 class="mb-3 text-primary mt-4"><i class="fas fa-desktop"></i> Periféricos</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="monitor" class="form-label">Monitor</label>
                    <input type="text" class="form-control" id="monitor" name="monitor" placeholder="ej. Samsung 24 pulgadas">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="keyboard" class="form-label">Teclado</label>
                    <input type="text" class="form-control" id="keyboard" name="keyboard" placeholder="ej. Logitech USB">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="mouse" class="form-label">Mouse</label>
                    <input type="text" class="form-control" id="mouse" name="mouse" placeholder="ej. Mouse Genius">
                </div>
            </div>

            <h5 class="mb-3 text-primary mt-4"><i class="fas fa-calendar"></i> Información Adicional</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="purchase_date" class="form-label">Fecha de Compra</label>
                    <input type="date" class="form-control" id="purchase_date" name="purchase_date">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="notes" class="form-label">Notas</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Observaciones adicionales..."></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                <i class="fas fa-save"></i> Guardar Equipo
            </button>
        </form>
    </div>
</div>
@endsection
