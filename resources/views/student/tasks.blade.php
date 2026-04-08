@extends('student.layout', ['student' => $student])

@section('title', 'Mis Tareas')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Mis Tareas</h2>
        <p class="text-muted">Grupo: {{ $student->group->name ?? 'Sin grupo asignado' }}</p>
    </div>
</div>

@if($tasks->count() > 0)
    <div class="row">
        @foreach($tasks as $task)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $task->title }}</h5>
                        @if($task->submissions->first())
                            @if($task->submissions->first()->grade !== null)
                                <span class="badge bg-success">Calificada: {{ $task->submissions->first()->grade }}</span>
                            @else
                                <span class="badge bg-info">Entregada</span>
                            @endif
                        @else
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <p>{{ $task->description }}</p>
                        
                        @if($task->attachments && count($task->attachments) > 0)
                        <div class="mb-3">
                            <strong><i class="fas fa-paperclip me-1"></i> Materiales adjuntos:</strong>
                            <div class="list-group mt-1">
                                @foreach($task->attachments as $attachment)
                                <a href="{{ asset('storage/' . $attachment['path']) }}" target="_blank" class="list-group-item list-group-item-action list-group-item-primary d-flex justify-content-between align-items-center">
                                    <span>
                                        @php
                                        $extension = pathinfo($attachment['name'], PATHINFO_EXTENSION);
                                        $icon = 'fa-file';
                                        if (in_array($extension, ['pdf'])) $icon = 'fa-file-pdf text-danger';
                                        elseif (in_array($extension, ['doc', 'docx'])) $icon = 'fa-file-word text-primary';
                                        elseif (in_array($extension, ['xls', 'xlsx'])) $icon = 'fa-file-excel text-success';
                                        elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) $icon = 'fa-file-image text-info';
                                        elseif (in_array($extension, ['mp4', 'avi', 'mov'])) $icon = 'fa-file-video text-purple';
                                        @endphp
                                        <i class="fas {{ $icon }} me-2"></i>{{ $attachment['name'] }}
                                    </span>
                                    <i class="fas fa-download"></i>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <div class="mb-2">
                            <strong><i class="fas fa-calendar me-1"></i> Fecha límite:</strong> 
                            {{ $task->due_date->format('d/m/Y H:i') }}
                        </div>
                        <div class="mb-2">
                            <strong><i class="fas fa-layer-group me-1"></i> Grupo:</strong> 
                            {{ $task->group->name ?? '' }}
                        </div>
                        
                        @if($task->submissions->first())
                            <div class="alert alert-secondary mt-3 mb-0">
                                <strong>Tu entrega:</strong>
                                <p class="mb-1">{{ $task->submissions->first()->content }}</p>
                                @if($task->submissions->first()->file_path)
                                    <div class="mt-2">
                                        <strong>Archivo adjuntado:</strong>
                                        <a href="{{ asset('storage/' . $task->submissions->first()->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                            <i class="fas fa-file-download"></i> Descargar archivo
                                        </a>
                                    </div>
                                @endif
                                @if($task->submissions->first()->grade !== null)
                                    <hr>
                                    <strong>Calificación: {{ $task->submissions->first()->grade }}</strong>
                                    @if($task->submissions->first()->feedback)
                                        <p class="mb-0 mt-2"><strong>Feedback:</strong> {{ $task->submissions->first()->feedback }}</p>
                                    @endif
                                @endif
                            </div>
                        @else
                            @if($task->due_date >= now())
                                <form method="POST" action="{{ route('student.tasks.submit', $task) }}" class="mt-3" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="form-label">Entregar tarea (texto):</label>
                                        <textarea name="content" class="form-control" rows="3" placeholder="Escribe tu respuesta aquí..."></textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">O adjuntar archivo:</label>
                                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.jpg,.jpeg,.png,.gif,.bmp">
                                        <small class="text-muted">PDF, Word, Excel, PowerPoint, imágenes o compressed (máx 20MB)</small>
                                    </div>
                                    <button type="submit" class="btn btn-student">
                                        <i class="fas fa-upload me-1"></i> Entregar
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-danger mt-3 mb-0">
                                    <i class="fas fa-exclamation-triangle me-1"></i> La fecha límite ha vencido.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-tasks fs-1 text-muted mb-3"></i>
            <h4>No hay tareas asignadas</h4>
            <p class="text-muted">No hay tareas disponibles para tu grupo en este momento.</p>
        </div>
    </div>
@endif
@endsection
