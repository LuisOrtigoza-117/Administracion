@extends('layouts.main')

@section('title', 'Reportar Daño')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-exclamation-triangle"></i> Reportar Daño</h1>
    <a href="{{ route('computers.reports') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('computers.reports.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="computer_id" class="form-label">Equipo</label>
                <select class="form-select" id="computer_id" name="computer_id" required>
                    <option value="">Seleccionar equipo</option>
                    @foreach($computers as $computer)
                    <option value="{{ $computer->id }}">{{ $computer->pc_number }} - {{ $computer->location }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripción del Daño</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="reported_by" class="form-label">Reportado por</label>
                    <input type="text" class="form-control" id="reported_by" name="reported_by" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="report_date" class="form-label">Fecha del Reporte</label>
                    <input type="date" class="form-control" id="report_date" name="report_date" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Reporte
            </button>
        </form>
    </div>
</div>
@endsection
