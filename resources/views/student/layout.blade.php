<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Portal - SchoolAdmin')</title>
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
            --student-primary: #3498db;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background);
            color: var(--text);
        }
        .student-sidebar {
            min-height: 100vh;
            background-color: var(--student-primary);
            color: white;
        }
        .student-sidebar a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s;
        }
        .student-sidebar a:hover, .student-sidebar a.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
        }
        .card {
            background: var(--card);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: none;
        }
        .stat-card {
            border-left: 4px solid var(--student-primary);
        }
        .btn-student {
            background-color: var(--student-primary);
            border-color: var(--student-primary);
        }
        .btn-student:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block student-sidebar py-3">
                <div class="text-center mb-3">
                    <img src="{{ asset('images/TECNICA LOGO.jpg') }}" alt="Logo" style="max-width: 80px; max-height: 80px; border-radius: 50%; margin-bottom: 10px;">
                    <h6 class="text-white mb-1" style="font-size: 11px;">ESCUELA SECUNDARIA<br>TECNICA No.53</h6>
                    <hr class="my-2">
                    <div class="mb-2">
                        <i class="fas fa-user-circle fa-2x"></i>
                    </div>
                    <h5>{{ $student->fullName ?? auth()->user()->name }}</h5>
                    <small class="text-white-50">Estudiante</small>
                    <div class="mt-2">
                        <a href="{{ route('profile.show') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-id-card"></i> Ver Perfil
                        </a>
                    </div>
                </div>
                <hr>
                <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i> Inicio
                </a>
                <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user me-2"></i> Mi Perfil
                </a>
                <a href="{{ route('student.tasks') }}" class="{{ request()->routeIs('student.tasks*') ? 'active' : '' }}">
                    <i class="fas fa-tasks me-2"></i> Mis Tareas
                </a>
                <a href="{{ route('student.attendance') }}" class="{{ request()->routeIs('student.attendance') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check me-2"></i> Mi Asistencia
                </a>
                <hr>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none w-100 text-start">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                    </button>
                </form>
            </nav>
            
            <main class="col-md-10 ms-sm-auto px-4 py-4">
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
        </div>
    </div>
    
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
