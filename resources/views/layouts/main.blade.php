<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SchoolAdmin - Laboratorio de Informática')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1e3a5f;
            --secondary: #3498db;
            --accent: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --background: #f5f7fa;
            --card: #ffffff;
            --text: #2c3e50;
            --text-light: #7f8c8d;
            --sidebar-width: 220px;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background);
            color: var(--text);
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background-color: var(--primary);
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        .sidebar a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: background-color 0.2s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        .card {
            background: var(--card);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: none;
        }
        .btn-primary {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .btn-success {
            background-color: var(--accent);
            border-color: var(--accent);
        }
        .btn-danger {
            background-color: var(--danger);
            border-color: var(--danger);
        }
        .table thead {
            background-color: var(--primary);
            color: white;
        }
        .table-striped > tbody > tr:nth-of-type(odd) {
            --bs-table-accent-bg: #f8f9fa;
        }
        .status-functional { color: var(--accent); }
        .status-damaged { color: var(--danger); }
        .status-repairing { color: var(--warning); }
        .status-retired { color: var(--text-light); }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                margin-bottom: 20px;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <nav class="sidebar py-3 d-none d-md-block">
        <div class="text-center mb-3">
            <img src="{{ asset('images/TECNICA LOGO.jpg') }}" alt="Logo" style="max-width: 70px; max-height: 70px; border-radius: 50%; margin-bottom: 10px;">
            <h6 class="text-white mb-1" style="font-size: 10px;">ESCUELA SECUNDARIA<br>TECNICA No.53</h6>
            <hr class="my-2">
            <div class="mb-2">
                <i class="fas fa-user-circle fa-2x"></i>
            </div>
            <h6 class="text-white mb-0" style="font-size: 13px;">{{ auth()->user()->name }}</h6>
            <small class="text-white-50">{{ auth()->user()->role === 'teacher' ? 'Profesor' : 'Estudiante' }}</small>
            <div class="mt-2">
                <a href="{{ route('profile.show') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-id-card"></i> Perfil
                </a>
            </div>
        </div>
        <hr>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-user-cog me-2"></i> Usuarios
        </a>
        <a href="{{ route('groups.index') }}" class="{{ request()->routeIs('groups.*') ? 'active' : '' }}">
            <i class="fas fa-users me-2"></i> Grupos
        </a>
        <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="fas fa-user-graduate me-2"></i> Estudiantes
        </a>
        <a href="{{ route('attendances.index') }}" class="{{ request()->routeIs('attendances.*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-check me-2"></i> Asistencia
        </a>
        <a href="{{ route('computers.index') }}" class="{{ request()->routeIs('computers.*') ? 'active' : '' }}">
            <i class="fas fa-desktop me-2"></i> Equipos
        </a>
        <a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}">
            <i class="fas fa-tasks me-2"></i> Tareas
        </a>
        <a href="{{ route('schedules.index') }}" class="{{ request()->routeIs('schedules.index') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt me-2"></i> Horarios
        </a>
        @if(auth()->user()->role === 'teacher')
        <a href="{{ route('teacher-schedules.index') }}" class="{{ request()->routeIs('teacher-schedules.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-check me-2"></i> Mi Horario
        </a>
        @endif
        <hr>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-white text-decoration-none w-100 text-start" style="color: rgba(255,255,255,0.8); padding: 12px 20px;">
                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
            </button>
        </form>
    </nav>
    
    <main class="main-content px-4 py-4">
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
        
        @yield('content')
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.alert').forEach(function(alert) {
                    let bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
