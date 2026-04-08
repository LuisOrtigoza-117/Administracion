@extends('layouts.main')

@section('title', 'Estudiantes')

@section('content')
<style>
@media print {
    .print-header { display: block !important; }
    .screen-header { display: none !important; }
    .btn, nav { display: none !important; }
    body { background: white; }
    .card { box-shadow: none; border: 1px solid #ddd; page-break-inside: avoid; }
    .card-header { display: none !important; }
    h1 { font-size: 18px; }
    table { font-size: 11px; width: 100%; }
    th, td { padding: 6px; }
    .no-print { display: none !important; }
    
    .print-hide { display: none !important; }
}
.print-header { display: none; }
</style>

<script>
function togglePrintOptions() {
    const options = document.getElementById('printOptions');
    options.style.display = options.style.display === 'none' ? 'block' : 'none';
}

function selectAllFields() {
    const checkboxes = document.querySelectorAll('.print-field');
    checkboxes.forEach(cb => cb.checked = true);
    updatePrintFields();
}

function updatePrintFields() {
    const checkboxes = document.querySelectorAll('.print-field:checked');
    const values = Array.from(checkboxes).map(cb => cb.value);
    document.getElementById('printFieldsInput').value = values.join(',');
}

function filterByGrade() {
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

// Initialize display based on URL params
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('print_fields')) {
        document.getElementById('printOptions').style.display = 'block';
    }
    filterByGrade();
});
</script>

@php
$groupId = request('group_id');
if ($groupId) {
    $students = $students->where('group_id', $groupId);
}
$groupedStudents = $students->groupBy('group_id');
$allGroups = \App\Models\Group::with('teacher')->orderBy('name')->get();

// Get print fields from request
$printFieldsInput = request('print_fields');
if (is_array($printFieldsInput)) {
    $printFields = $printFieldsInput;
} elseif ($printFieldsInput) {
    $printFields = explode(',', $printFieldsInput);
} else {
    $printFields = ['student_number', 'name', 'email', 'phone', 'grade', 'section'];
}
@endphp

<div class="Print-header">
    @if($groupId)
        @php $selectedGroup = \App\Models\Group::find($groupId); @endphp
        <h1 style="text-align: center;">LISTADO DE ESTUDIANTES</h1>
        <p style="text-align: center; font-size: 12px;">Grado: {{ $selectedGroup->grade }} | Sección: {{ $selectedGroup->section }} | Grupo: {{ $selectedGroup->name }}</p>
        <p style="text-align: center; font-size: 12px;">Docente: {{ $selectedGroup->teacher->name ?? 'Sin docente' }}</p>
    @else
        <h1 style="text-align: center;">LISTADO DE ESTUDIANTES</h1>
    @endif
    <p style="text-align: center; font-size: 12px;">Fecha de impresion: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 screen-header">
    <h1><i class="fas fa-user-graduate"></i> Estudiantes</h1>
    <div>
        <button onclick="togglePrintOptions()" class="btn btn-info">
            <i class="fas fa-cog"></i> Opciones de Impresión
        </button>
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <a href="{{ route('students.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Estudiante
        </a>
    </div>
</div>

<div class="card mb-4 screen-header" id="printOptions" style="display: none;">
    <div class="card-body">
        <h6><i class="fas fa-check-square"></i> Seleccionar campos a imprimir:</h6>
        <form method="GET" id="printForm">
            @if($groupId)
            <input type="hidden" name="group_id" value="{{ $groupId }}">
            @endif
            <input type="hidden" name="print_fields" id="printFieldsInput" value="{{ implode(',', $printFields) }}">
            <div class="row mt-2">
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input print-field" type="checkbox" value="student_number" id="pf_student_number" {{ in_array('student_number', $printFields) ? 'checked' : '' }} onchange="updatePrintFields()">
                        <label class="form-check-label" for="pf_student_number">No. Control</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input print-field" type="checkbox" value="name" id="pf_name" {{ in_array('name', $printFields) ? 'checked' : '' }} onchange="updatePrintFields()">
                        <label class="form-check-label" for="pf_name">Nombre</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input print-field" type="checkbox" value="email" id="pf_email" {{ in_array('email', $printFields) ? 'checked' : '' }} onchange="updatePrintFields()">
                        <label class="form-check-label" for="pf_email">Email</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input print-field" type="checkbox" value="phone" id="pf_phone" {{ in_array('phone', $printFields) ? 'checked' : '' }} onchange="updatePrintFields()">
                        <label class="form-check-label" for="pf_phone">Teléfono</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input print-field" type="checkbox" value="grade" id="pf_grade" {{ in_array('grade', $printFields) ? 'checked' : '' }} onchange="updatePrintFields()">
                        <label class="form-check-label" for="pf_grade">Grado</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input print-field" type="checkbox" value="section" id="pf_section" {{ in_array('section', $printFields) ? 'checked' : '' }} onchange="updatePrintFields()">
                        <label class="form-check-label" for="pf_section">Sección</label>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter"></i> Aplicar
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="selectAllFields()">
                        Seleccionar Todos
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mb-4 screen-header">
    <div class="card-body">
        <form method="GET" class="row g-3" id="filterForm">
            <div class="col-md-3">
                <label for="grade" class="form-label">Filtrar por Grado</label>
                <select name="grade" id="grade" class="form-select" onchange="filterByGrade()">
                    <option value="">Todos los grados</option>
                    <option value="1" {{ ($grade ?? '') == '1' ? 'selected' : '' }}>1° Año</option>
                    <option value="2" {{ ($grade ?? '') == '2' ? 'selected' : '' }}>2° Año</option>
                    <option value="3" {{ ($grade ?? '') == '3' ? 'selected' : '' }}>3° Año</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="group_id" class="form-label">Filtrar por Grupo</label>
                <select name="group_id" id="group_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Todos los grupos</option>
                    @foreach($allGroups as $group)
                        <option value="{{ $group->id }}" data-grade="{{ $group->grade }}" {{ $groupId == $group->id ? 'selected' : '' }}>
                            {{ $group->name }} - {{ $group->teacher->name ?? 'Sin docente' }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if($groupId)
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('students.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Quitar
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

@forelse($groupedStudents as $groupId => $groupStudents)
    @if($groupId)
        @php
            $group = $groupStudents->first()->group;
        @endphp
        <div class="card mb-4">
            <div class="card-header bg-primary text-white no-print">
                <h5 class="mb-0"><i class="fas fa-users"></i> Grupo: {{ $group->name ?? 'Sin grupo' }}</h5>
                @if($group && $group->teacher)
                    <small><i class="fas fa-chalkboard-teacher"></i> Docente: {{ $group->teacher->name }}</small>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="{{ !in_array('student_number', $printFields) ? 'print-hide' : '' }}">No. Control</th>
                                <th class="{{ !in_array('name', $printFields) ? 'print-hide' : '' }}">Nombre</th>
                                <th class="{{ !in_array('email', $printFields) ? 'print-hide' : '' }}">Email</th>
                                <th class="{{ !in_array('phone', $printFields) ? 'print-hide' : '' }}">Teléfono</th>
                                <th class="{{ !in_array('grade', $printFields) ? 'print-hide' : '' }}">Grado</th>
                                <th class="{{ !in_array('section', $printFields) ? 'print-hide' : '' }}">Sección</th>
                                <th class="no-print">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupStudents as $student)
                            <tr>
                                <td class="{{ !in_array('student_number', $printFields) ? 'print-hide' : '' }}">{{ $student->student_number }}</td>
                                <td class="{{ !in_array('name', $printFields) ? 'print-hide' : '' }}">{{ $student->name }} {{ $student->lastname }}</td>
                                <td class="{{ !in_array('email', $printFields) ? 'print-hide' : '' }}">{{ $student->email }}</td>
                                <td class="{{ !in_array('phone', $printFields) ? 'print-hide' : '' }}">{{ $student->phone ?? '-' }}</td>
                                <td class="{{ !in_array('grade', $printFields) ? 'print-hide' : '' }}">{{ $group->grade ?? '-' }}</td>
                                <td class="{{ !in_array('section', $printFields) ? 'print-hide' : '' }}">{{ $group->section ?? '-' }}</td>
                                <td class="no-print">
                                    <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white no-print">
                <h5 class="mb-0"><i class="fas fa-user-times"></i> Sin Grupo</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="{{ !in_array('student_number', $printFields) ? 'print-hide' : '' }}">No. Control</th>
                                <th class="{{ !in_array('name', $printFields) ? 'print-hide' : '' }}">Nombre</th>
                                <th class="{{ !in_array('email', $printFields) ? 'print-hide' : '' }}">Email</th>
                                <th class="{{ !in_array('phone', $printFields) ? 'print-hide' : '' }}">Teléfono</th>
                                <th class="{{ !in_array('grade', $printFields) ? 'print-hide' : '' }}">Grado</th>
                                <th class="{{ !in_array('section', $printFields) ? 'print-hide' : '' }}">Sección</th>
                                <th class="no-print">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupStudents as $student)
                            <tr>
                                <td class="{{ !in_array('student_number', $printFields) ? 'print-hide' : '' }}">{{ $student->student_number }}</td>
                                <td class="{{ !in_array('name', $printFields) ? 'print-hide' : '' }}">{{ $student->name }} {{ $student->lastname }}</td>
                                <td class="{{ !in_array('email', $printFields) ? 'print-hide' : '' }}">{{ $student->email }}</td>
                                <td class="{{ !in_array('phone', $printFields) ? 'print-hide' : '' }}">{{ $student->phone ?? '-' }}</td>
                                <td class="{{ !in_array('grade', $printFields) ? 'print-hide' : '' }}">-</td>
                                <td class="{{ !in_array('section', $printFields) ? 'print-hide' : '' }}">-</td>
                                <td class="no-print">
                                    <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@empty
<div class="card">
    <div class="card-body">
        <p class="text-center text-muted">No hay estudiantes registrados</p>
    </div>
</div>
@endforelse
@endsection
