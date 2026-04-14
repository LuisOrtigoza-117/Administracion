@extends('layouts.main')

@section('title', 'Tarea: ' . $task->title)

@section('content')
<style>
@media print {
    .btn, nav, .modal { display: none !important; }
    body { background: white; }
    .card { box-shadow: none; border: 1px solid #ddd; }
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-tasks"></i> {{ $task->title }}</h1>
    <div>
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Información de la Tarea</h5>
                <p><strong>Grupos:</strong>
                    @php
                    $taskGroups = \App\Models\Group::whereIn('id', $task->group_ids ?? [])->get();
                    @endphp
                    @foreach($taskGroups as $g)
                        <span class="badge bg-primary me-1">{{ $g->name }}</span>
                    @endforeach
                </p>
                <p><strong>Fecha de Entrega:</strong> {{ $task->due_date->format('d/m/Y') }}</p>
                <p><strong>Puntos Máximos:</strong> {{ $task->max_points }}</p>
                <p>
                    <strong>Estado:</strong>
                    @if($task->due_date->isPast())
                        <span class="badge bg-danger">Vencida</span>
                    @else
                        <span class="badge bg-success">Activa</span>
                    @endif
                </p>
                @if($task->description)
                <hr>
                <p><strong>Descripción:</strong></p>
                <p>{{ $task->description }}</p>
                @endif
            </div>
        </div>

        @if($task->attachments && count($task->attachments) > 0)
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-paperclip"></i> Materiales Adjuntos</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($task->attachments as $attachment)
                    <a href="{{ asset('storage/' . $attachment['path']) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            @php
                            $extension = pathinfo($attachment['name'], PATHINFO_EXTENSION);
                            $icon = 'fa-file';
                            $color = 'text-secondary';
                            
                            if (in_array($extension, ['pdf'])) { $icon = 'fa-file-pdf'; $color = 'text-danger'; }
                            elseif (in_array($extension, ['doc', 'docx'])) { $icon = 'fa-file-word'; $color = 'text-primary'; }
                            elseif (in_array($extension, ['xls', 'xlsx'])) { $icon = 'fa-file-excel'; $color = 'text-success'; }
                            elseif (in_array($extension, ['ppt', 'pptx'])) { $icon = 'fa-file-powerpoint'; $color = 'text-warning'; }
                            elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) { $icon = 'fa-file-image'; $color = 'text-info'; }
                            elseif (in_array($extension, ['mp4', 'avi', 'mov', 'mkv'])) { $icon = 'fa-file-video'; $color = 'text-purple'; }
                            @endphp
                            <i class="fas {{ $icon }} {{ $color }} me-2"></i>
                            {{ $attachment['name'] }}
                        </div>
                        <i class="fas fa-download"></i>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Entregas ({{ $task->submissions->count() }}/{{ isset($students) ? $students->count() : 0 }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Estado</th>
                                <th>Fecha Entrega</th>
                                <th>Entrega</th>
                                <th>Calificación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($task->submissions as $submission)
                            <tr>
                                <td>{{ $submission->student->name }} {{ $submission->student->lastname }}</td>
                                <td>
                                    @switch($submission->status)
                                        @case('pending')
                                            <span class="badge bg-secondary">Pendiente</span>
                                            @break
                                        @case('submitted')
                                            <span class="badge bg-info">Entregado</span>
                                            @break
                                        @case('graded')
                                            <span class="badge bg-success">Calificado</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    @if($submission->content || $submission->file_path)
                                        <div class="d-flex flex-column gap-1">
                                            @if($submission->content)
                                                <span class="badge bg-info">
                                                    <i class="fas fa-align-left"></i> Texto
                                                </span>
                                            @endif
                                            @if($submission->file_path)
                                                @php
                                                $fileName = basename($submission->file_path);
                                                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                                                $icon = 'fa-file';
                                                $color = 'text-success';
                                                
                                                if (in_array($extension, ['pdf'])) { $icon = 'fa-file-pdf'; $color = 'text-danger'; }
                                                elseif (in_array($extension, ['doc', 'docx'])) { $icon = 'fa-file-word'; $color = 'text-primary'; }
                                                elseif (in_array($extension, ['xls', 'xlsx'])) { $icon = 'fa-file-excel'; $color = 'text-success'; }
                                                elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) { $icon = 'fa-file-image'; $color = 'text-info'; }
                                                @endphp
                                                <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas {{ $icon }}"></i> Ver Archivo
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($submission->grade !== null)
                                        {{ $submission->grade }}/{{ $task->max_points }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($submission->status == 'submitted')
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#gradeModal{{ $submission->id }}">
                                        <i class="fas fa-star"></i> Calificar
                                    </button>
                                    <div class="modal fade" id="gradeModal{{ $submission->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Calificar: {{ $submission->student->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('tasks.submissions.grade', $submission) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        @if($submission->content || $submission->file_path)
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Entrega del estudiante:</label>
                                                                @if($submission->content)
                                                                    <div class="p-3 bg-light rounded mb-3">
                                                                        <p class="mb-0">{{ $submission->content }}</p>
                                                                    </div>
                                                                @endif
                                                                @if($submission->file_path)
                                                                    @php
                                                                        $fileUrl = asset('storage/' . $submission->file_path);
                                                                        $fileExtension = strtolower(pathinfo($submission->file_path, PATHINFO_EXTENSION));
                                                                        $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                                                                        $isPdf = $fileExtension === 'pdf';
                                                                    @endphp
                                                                    <div class="border rounded p-3">
                                                                        <div class="mb-2">
                                                                            <i class="fas fa-file me-2"></i>
                                                                            <strong>Archivo:</strong> {{ basename($submission->file_path) }}
                                                                        </div>
                                                                        @if($isImage)
                                                                            <div class="text-center mb-3">
                                                                                <img src="{{ $fileUrl }}" alt="Archivo" class="img-fluid rounded" style="max-height: 300px; cursor: pointer;" onclick="window.open('{{ $fileUrl }}', '_blank')">
                                                                                <p class="text-muted small mb-0">Clic en la imagen para ver en grande</p>
                                                                            </div>
                                                                        @elseif($isPdf)
                                                                            <iframe src="{{ $fileUrl }}" width="100%" height="300px" class="rounded border mb-2"></iframe>
                                                                        @endif
                                                                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                            <i class="fas fa-external-link-alt"></i> Ver archivo completo
                                                                        </a>
                                                                        <a href="{{ $fileUrl }}" download class="btn btn-sm btn-success">
                                                                            <i class="fas fa-download"></i> Descargar
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        <hr>
                                                        <div class="mb-3">
                                                            <label class="form-label">Calificación</label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="grade" min="0" max="{{ $task->max_points }}" step="0.5" value="{{ $submission->grade ?? '' }}" required>
                                                                <span class="input-group-text">/ {{ $task->max_points }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Retroalimentación</label>
                                                            <textarea class="form-control" name="feedback" rows="3" placeholder="Escribe comentarios para el estudiante...">{{ $submission->feedback ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-save"></i> Guardar calificación
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay entregas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
