@extends('layouts.main')

@section('title', 'Editar Tarea')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit"></i> Editar Tarea</h1>
    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="group_ids" class="form-label">Grupos <span class="text-danger">*</span></label>
                <select class="form-select" id="group_ids" name="group_ids[]" multiple required size="{{ min(5, count($groups)) }}">
                    @php $selectedGroups = is_array($task->group_ids) ? $task->group_ids : []; @endphp
                    @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ in_array($group->id, $selectedGroups) ? 'selected' : '' }}>
                        {{ $group->name }} - Grado: {{ $group->grade }}, Sección: {{ $group->section }}
                    </option>
                    @endforeach
                </select>
                <small class="text-muted">Mantén presionada la tecla Ctrl (o Cmd en Mac) para seleccionar múltiples grupos</small>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label">Título</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $task->title }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="due_date" class="form-label">Fecha de Entrega</label>
                    <input type="date" class="form-control" id="due_date" name="due_date" value="{{ $task->due_date->toDateString() }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" rows="4">{{ $task->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="max_points" class="form-label">Puntos Máximos</label>
                <input type="number" class="form-control" id="max_points" name="max_points" value="{{ $task->max_points }}" min="0" step="0.5" required>
            </div>

            <div class="card bg-light mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-paperclip"></i> Materiales Adjuntos</h5>
                </div>
                <div class="card-body">
                    @if($task->attachments && count($task->attachments) > 0)
                    <div class="mb-3">
                        <label class="form-label">Archivos actuales:</label>
                        <div class="list-group mb-3">
                            @foreach($task->attachments as $index => $attachment)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-file me-2"></i>{{ $attachment['name'] }}</span>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeAttachment({{ $index }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="attachments" class="form-label">Agregar más archivos:</label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov,.mkv">
                        <small class="text-muted">Puedes seleccionar múltiples archivos. Tamaño máximo: 20MB por archivo.</small>
                    </div>
                    <div id="fileList" class="list-group"></div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar Tarea
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('attachments').addEventListener('change', function(e) {
    const fileList = document.getElementById('fileList');
    fileList.innerHTML = '';
    
    for (let i = 0; i < this.files.length; i++) {
        const file = this.files[i];
        const item = document.createElement('div');
        item.className = 'list-group-item d-flex justify-content-between align-items-center';
        
        let icon = 'fa-file';
        if (file.type.includes('pdf')) icon = 'fa-file-pdf text-danger';
        else if (file.type.includes('word') || file.type.includes('document')) icon = 'fa-file-word text-primary';
        else if (file.type.includes('excel') || file.type.includes('sheet')) icon = 'fa-file-excel text-success';
        else if (file.type.includes('image')) icon = 'fa-file-image text-warning';
        else if (file.type.includes('video')) icon = 'fa-file-video text-purple';
        
        item.innerHTML = `
            <span><i class="fas ${icon} me-2"></i>${file.name}</span>
            <span class="badge bg-secondary">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
        `;
        fileList.appendChild(item);
    }
});
</script>
@endsection
