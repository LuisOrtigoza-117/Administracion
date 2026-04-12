<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E.S.T. No. 53</title>
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
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        .login-header {
            background-color: var(--primary);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-header img {
            max-width: 100px;
            margin-bottom: 15px;
        }
        .login-body {
            padding: 30px;
        }
        .form-control:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        .btn-login {
            background-color: var(--secondary);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-login:hover {
            background-color: #2980b9;
        }
        .user-type-tabs {
            display: flex;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 20px;
        }
        .user-type-tab {
            flex: 1;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            border: none;
            background: transparent;
            color: #6c757d;
            font-weight: 600;
            transition: all 0.3s;
        }
        .user-type-tab.active {
            color: var(--secondary);
            border-bottom: 2px solid var(--secondary);
            margin-bottom: -2px;
        }
        .user-type-tab:hover {
            color: var(--secondary);
        }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <!-- Logo de la escuela -->
                <img src="{{ asset('images/TECNICA LOGO.jpg') }}" alt="Logo E.S.T. No. 53" style="max-width: 120px; max-height: 120px; margin-bottom: 15px;">
                <h3><i class="fas fa-school"></i> E.S.T. No. 53</h3>
                <p class="mb-0">Laboratorio de Informática</p>
            </div>
            <div class="login-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="user-type-tabs">
                    <button type="button" class="user-type-tab active" data-tab="student">
                        <i class="fas fa-user-graduate"></i> Alumno
                    </button>
                    <button type="button" class="user-type-tab" data-tab="teacher">
                        <i class="fas fa-chalkboard-teacher"></i> Maestro
                    </button>
                </div>

                <form method="POST" action="{{ route('login') }}" id="login-form" class="tab-content active" data-tab-content="student">
                    @csrf
                    <input type="hidden" name="user_type" value="student">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-login w-100">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión como Alumno
                    </button>
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            <i class="fas fa-key"></i> ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                    <hr>
                    <div class="text-center">
                        <p class="text-muted mb-2">¿No tienes cuenta?</p>
                        <a href="{{ route('register', 'student') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-user-plus"></i> Registrarse como Alumno
                        </a>
                    </div>
                </form>

                <form method="POST" action="{{ route('login') }}" id="login-form-teacher" class="tab-content" data-tab-content="teacher">
                    @csrf
                    <input type="hidden" name="user_type" value="teacher">
                    <div class="mb-3">
                        <label for="email_teacher" class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email_teacher" name="email" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password_teacher" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password_teacher" name="password" required>
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember_teacher" name="remember">
                        <label class="form-check-label" for="remember_teacher">Recordarme</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-login w-100">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión como Maestro
                    </button>
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            <i class="fas fa-key"></i> ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                    <hr>
                    <div class="text-center">
                        <p class="text-muted mb-2">¿No tienes cuenta?</p>
                        <a href="{{ route('register', 'teacher') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-user-plus"></i> Registrarse como Maestro
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.user-type-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const tabName = this.dataset.tab;
                
                document.querySelectorAll('.user-type-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                    if (content.dataset.tabContent === tabName) {
                        content.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
