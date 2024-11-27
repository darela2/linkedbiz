<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LinkedBiz</title>
    <script>
        // Pasar las rutas desde Blade a JavaScript
        const registerCompanyUrl = "{{ route('register.company') }}";
        const registerUserUrl= "{{ route('register.user') }}";
        const passwordResetUrl = "{{ route('password.sendResetLink') }}";
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="resources/css/acceso.css">
    <link rel="icon" href="resources/views/logo-linkedbiz.png" type="image/png">

</head>
<body>

    <!-- Header -->
    <header role="banner">
        <img src="resources/views/logo-linkedbiz.png" alt="LinkedBiz Logo" class="logo">
    </header>

    <!-- Contenido principal -->
    <main role="main">
        <div class="container my-5">
            <div class="row">
                <div class="col-md-12 text-center">

                    <!-- Título --> 
                    <h1 class="mb-4">Bienvenid@ a LinkedBiz</h1>

                    <!-- Pestañas de Registro e Inicio de Sesión -->
                    <ul class="nav nav-tabs" id="mainAuthTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="register-main-tab" data-bs-toggle="tab" data-bs-target="#register-main" type="button" role="tab" aria-controls="register-main" aria-selected="true">Registro</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="login-main-tab" data-bs-toggle="tab" data-bs-target="#login-main" type="button" role="tab" aria-controls="login-main" aria-selected="false">Inicio de Sesión</button>
                        </li>
                    </ul>

                    <!-- Contenido de Pestañas de Registro e Inicio de Sesión -->
                    <!-- Contenido de Pestañas -->
                    <div class="tab-content mt-4" id="mainAuthTabContent">
                        
                        <!-- Pestaña Principal de Registro -->
                        <div class="tab-pane fade show active" id="register-main" role="tabpanel" aria-labelledby="register-main-tab">
                            <!-- Pestañas internas para Registro de Usuario o Empresa -->
                            <ul class="nav nav-pills mb-3" id="registerTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="user-register-tab" data-bs-toggle="tab" data-bs-target="#user-register" type="button" role="tab" aria-controls="user-register" aria-selected="true">Usuario</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="company-register-tab" data-bs-toggle="tab" data-bs-target="#company-register" type="button" role="tab" aria-controls="company-register" aria-selected="false">Empresa</button>
                                </li>
                            </ul>

                            <!-- Contenido de Pestañas Internas de Registro -->
                            <div class="tab-content" id="registerTabContent">
                                <!-- Registro de Usuario -->
                                <div class="tab-pane fade show active" id="user-register" role="tabpanel" aria-labelledby="user-register-tab">
                                    <div class="form-container">
                                        <h3 class="mb-3">Registro de Usuario</h3>
                                        <form id="userRegisterForm" action="{{ route('register.user') }}" method="POST">
                                            <div class="mb-3">
                                                <label for="userName" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="userName" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="userSurname" class="form-label">Apellidos</label>
                                                <input type="text" class="form-control" id="userSurname" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="userBiography" class="form-label">Biograf&iacute;a</label>
                                                <input type="textarea" class="form-control" id="userBiography" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="user" class="form-label">Nombre Usuario</label>
                                                <input type="text" class="form-control" id="user" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="userPassword" class="form-label">Contraseña</label>
                                                <div class="password-wrapper">
                                                    <input type="password" class="form-control" id="userPassword" required>
                                                    <button type="button" class="toggle-password">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="userEmail" class="form-label">Correo Electrónico</label>
                                                <input type="email" class="form-control" id="userEmail" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="userBirthDate" class="form-label">Fecha Nacimiento</label>
                                                <input type="date" class="form-control" id="userBirthDate" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="userPhoto" class="form-label">Fotograf&iacute;a</label>
                                                <input type="file" class="form-control" id="userPhoto" accept="image/*">
                                            </div>
                                            <div class="mb-3">
                                                <label for="company" class="form-label">Empresa</label>
                                                <select name="company" class="form-control" id="company">
                                                    @foreach($empresas as $empresa)
                                                    <option value="{{ $empresa->CIF }}">{{ $empresa->Nombre }}</option>
                                                    @endforeach 
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Registro de Empresa -->
                                <div class="tab-pane fade" id="company-register" role="tabpanel" aria-labelledby="company-register-tab">
                                    <div class="form-container">
                                        <h3 class="mb-3">Registro de Empresa</h3>
                                        <form id="companyRegisterForm" action="{{ route('register.company') }}" method="POST">
                                        @csrf
                                            <div class="mb-3">
                                                <label for="companyID" class="form-label">CIF</label>
                                                <input type="text" class="form-control" id="companyID" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="companyName" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="companyName" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="companyLocation" class="form-label">Direcci&oacute;n</label>
                                                <input type="text" class="form-control" id="companyLocation">
                                            </div>
                                            <div class="mb-3">
                                                <label for="companyCity" class="form-label">Localidad</label>
                                                <input type="text" class="form-control" id="companyCity">
                                            </div>
                                            <div class="mb-3">
                                                <label for="companyEmail" class="form-label">Correo Electrónico</label>
                                                <input type="email" class="form-control" id="companyEmail">
                                            </div>
                                            <div class="mb-3">
                                                <label for="companyPhone" class="form-label">Tel&eacute;fono</label>
                                                <input type="number" class="form-control" id="companyPhone">
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100">Registrar Empresa</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Modal para éxito o error -->
                                <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="feedbackModalLabel">Mensaje</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" id="modalBody">
                                                <!-- Aquí se mostrará el mensaje de éxito o error -->
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Pestaña de Inicio de Sesión -->
                        <div class="tab-pane fade" id="login-main" role="tabpanel" aria-labelledby="login-main-tab">
                            <div class="form-container">
                                <h3 class="mb-3">Inicio de Sesión</h3>
                                <form id="loginForm" action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="loginName" class="form-label">Nombre Usuario</label>
                                        <input type="text" class="form-control" id="loginName" name="loginName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="loginPassword" class="form-label">Contraseña</label>
                                        <div class="password-wrapper">
                                            <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
                                            <button type="button" class="toggle-password-login">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                                    <!-- Botón para abrir modal de recuperación de contraseña -->
                                    <div class="mt-3 text-center">
                                        <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">¿Olvidaste tu contraseña?</button>
                                    </div>
                                </form>

                                <!-- Modal de recuperación de contraseña -->
                                <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="forgotPasswordModalLabel">Recuperar Contraseña</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="forgotPasswordForm">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="forgotPasswordEmail" class="form-label">Introduce tu correo electrónico</label>
                                                        <input type="email" class="form-control" id="forgotPasswordEmail" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary w-100">Enviar correo de recuperación</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <!-- Modal de error de inicio de sesión -->
                                <div class="modal fade" id="loginErrorModal" tabindex="-1" aria-labelledby="loginErrorModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="loginErrorModalLabel">Error de Inicio de Sesión</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p id="loginErrorMessage"></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <!-- Grid container -->
            <div class="container d-flex flex-column justify-content-center align-items-center pt-4">
                <!-- Section: Social media -->
                <section class="mb-4">
                <!-- Facebook -->
                <a class="btn btn-link btn-floating btn-lg text-white m-1" href="https://www.facebook.com" role="button"><i class="fab fa-facebook-f"></i></a>
                
                <!-- Twitter -->
                <a class="btn btn-link btn-floating btn-lg text-white m-1" href="https://x.com" role="button"><i class="fab fa-twitter"></i></a>
                
                <!-- TikTok -->
                <a class="btn btn-link btn-floating btn-lg text-white m-1" href="https://www.tiktok.com" role="button"><i class="fab fa-tiktok"></i></a>
                
                <!-- Instagram -->
                <a class="btn btn-link btn-floating btn-lg text-white m-1" href="https://www.instagram.com" role="button"><i class="fab fa-instagram"></i></a>
                
                <!-- LinkedIn -->
                <a class="btn btn-link btn-floating btn-lg text-white m-1" href="https://es.linkedin.com" role="button"><i class="fab fa-linkedin"></i></a>
                
                <!-- Github -->
                <a class="btn btn-link btn-floating btn-lg text-white m-1" href="https://www.github.com" role="button"><i class="fab fa-github"></i></a>
                </section>
                <!-- Section: Social media -->
            </div>
            <!-- Grid container -->

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                &copy; 2024 LinkedBiz. Todos los derechos reservados.
                <a class="text-white" href="https://creativecommons.org/licenses/by-nc/4.0/">Licencia Creative Commons</a>
            </div>
            <!-- Copyright -->
        </div>
    </main>
    
    

    
    


    <!-- Scripts -->
    <script type="module" src="resources/js/registrarEmpresa.js"></script>
    <script type="module" src="resources/js/registrarUsuario.js"></script>
    <script type="module" src="resources/js/login.js"></script>
    <script type="module" src="resources/js/recuperarContraseña.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
