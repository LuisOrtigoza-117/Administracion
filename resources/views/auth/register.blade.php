<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - E.S.T. No. 53</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1e3a5f;
            --secondary: #3498db;
            --background: #f5f7fa;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background);
        }
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .register-header {
            background-color: var(--primary);
            color: white;
            padding: 25px;
            text-align: center;
        }
        .register-body {
            padding: 30px;
        }
        .form-control:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        .btn-register {
            background-color: var(--secondary);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-register:hover {
            background-color: #2980b9;
        }
        .user-type-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .badge-student {
            background-color: #e8f4fc;
            color: #2980b9;
        }
        .badge-teacher {
            background-color: #fdf2e9;
            color: #e67e22;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                @if($type === 'student')
                    <span class="user-type-badge badge-student">
                        <i class="fas fa-user-graduate"></i> Registro de Alumno
                    </span>
                @else
                    <span class="user-type-badge badge-teacher">
                        <i class="fas fa-chalkboard-teacher"></i> Registro de Maestro
                    </span>
                @endif
                <h4 class="mt-3 mb-1"><i class="fas fa-school"></i> E.S.T. No. 53</h4>
                <p class="mb-0 text-white-50">Crea tu cuenta</p>
            </div>
            <div class="register-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register', $type) }}">
                    @csrf

                    @if($type === 'student')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nombre(s)</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastname" class="form-label">Apellido(s)</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="student_number" class="form-label">Número de Control</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                <input type="text" class="form-control" id="student_number" name="student_number" value="{{ old('student_number') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="group_id" class="form-label">Grupo</label>
                            <select class="form-select" id="group_id" name="group_id" required>
                                <option value="">Selecciona tu grupo</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }} - {{ $group->grade }}°{{ $group->section }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico (opcional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="opcional">
                            </div>
                        </div>
                    @else
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre Completo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Teléfono (opcional)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="specialty" class="form-label">Especialidad (opcional)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                    <input type="text" class="form-control" id="specialty" name="specialty" value="{{ old('specialty') }}" placeholder="ej. Informática">
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-register w-100">
                        <i class="fas fa-user-plus"></i> Crear Cuenta
                    </button>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('login.form') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left"></i> Volver al login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
