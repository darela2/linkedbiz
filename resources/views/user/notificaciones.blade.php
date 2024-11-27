<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->ID }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="resources/views/logo-linkedbiz.png" type="image/png">
    <link rel="stylesheet" href="resources/css/notificaciones.css">
    <title>Notificaciones - LinkedBiz</title>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar-bg">
        <div class="container-fluid">
            <div class="d-flex justify-content-between w-100">
                <!-- Botones de la izquierda -->
                <div class="navbar-left">
                    <a class="btn btn-light" href="{{ route('user.dashboard') }}">Inicio</a>
                    <a class="btn btn-light" href="{{ route('searchUsers') }}">Buscar usuarios</a>
                </div>

                <!-- Logo centrado -->
                <a class="navbar-brand mx-auto" href="#">
                    <img src="resources/views/logo-linkedbiz.png" alt="LinkedBiz Logo" class="logo">
                </a>

                <!-- Botones de la derecha -->
                <div class="navbar-right">
                    <!-- Botón desplegable de opciones -->
                    <div class="dropdown dropdown-hover">
                        <a class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Opciones
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                            <!-- Cambiar tema -->
                            <li>
                                <a class="dropdown-item" href="#" id="toggleTheme">Tema Oscuro</a>
                            </li>

                            <!-- Cambiar idioma -->
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">Cambiar idioma</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" id="langEsp">&#x1F1EA;&#x1F1F8; Español</a></li>
                                    <li><a class="dropdown-item" href="#" id="langEng">&#x1F1EC;&#x1F1E7; English</a></li>
                                    <li><a class="dropdown-item" href="#" id="langFr">&#x1F1EE;&#x1F1F9; Italiano</a></li>
                                </ul>
                            </li>

                            <!-- Ajuste de texto -->
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">Ajuste de texto</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" id="increaseText">Aumentar texto</a></li>
                                    <li><a class="dropdown-item" href="#" id="decreaseText">Disminuir texto</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <a class="btn btn-light" href="{{ route('home') }}">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal de Notificaciones -->
    <main class="container mt-4 mb-5">
        <h2 class="text-center mb-4">Notificaciones</h2>

        <div class="list-group">
            <!-- Aquí irán las notificaciones -->
        </div>
        <!-- Modal -->
        <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalMessageBody">
                    <!-- Aquí se mostrará el mensaje -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
    <script type="module" src="resources/js/notificaciones.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>