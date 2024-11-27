<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->ID }}">
    <title>Perfil de usuario - LinkedBiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../resources/css/dashboard.css">
    <link rel="icon" href="../resources/views/logo-linkedbiz.png" type="image/png">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar-bg">
        <div class="container-fluid">
            <div class="d-flex justify-content-between w-100">
                <!-- Botones de la izquierda -->
                <div class="navbar-left">
                    <a class="btn btn-light" href="{{ route('user.dashboard') }}">Inicio</a>
                    <a class="btn btn-light" href="{{ route('notificaciones') }}">Notificaciones</a>
                </div>

                <!-- Logo centrado -->
                <a class="navbar-brand mx-auto" href="#">
                    <img src="../resources/views/logo-linkedbiz.png" alt="LinkedBiz Logo" class="logo">
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

    <main>
        <div class="container mt-5">
            <div class="row">
                <!-- Perfil de usuario -->
                <div class="col-md-4">
                    <div class="card">
                        <img id="userPhoto" class="card-img-top" alt="Foto del usuario" src="{{ $usuario->Fotografia }}">
                        <div class="card-body">
                            <h5 class="card-text" id="userName">{{ $usuario->Nombre }} {{ $usuario->Apellidos }}</h5>
                            <p class="card-text" id="userCallName">{{ $usuario->Usuario }}</p>
                            <p class="card-text" id="userBiography">{{ $usuario->Biografia }}</p>
                            <p class="card-text" id="userEmail">{{ $usuario->Email }}</p>
                            <p class="card-text" id="userBirthDate">{{ $usuario->FechaNacimiento }}</p>
                            <p class="card-text" id="userCompany">{{ $usuario->empresa->Nombre }}</p>
                        </div>
                    </div>
                </div>

                <!-- Publicaciones y Lista de amigos -->
                <div class="col-md-8">
                    <div class="row">
                        <!-- Publicaciones del usuario -->
                        <div class="col-md-9">
                            <h3>Publicaciones de <span id="userCallName">{{ $usuario->Usuario }}</span></h3>
                            <div id="userPosts" class="mt-4">
                                <!-- Mostrar publicaciones del usuario -->
                                @foreach($publicaciones as $publicacion)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <!-- Mostrar el contenido de la publicación -->
                                        <h5 class="card-title">{{ $publicacion->Titulo }}</h5>
                                        <p class="card-text">{{ $publicacion->Contenido }}</p>

                                        @if($publicacion->Imagen)
                                        <img src="../storage/app/public/{{ $publicacion->Imagen }}">
                                        @endif
                                        <br>
                                        <!-- Mostrar fecha en formato día/mes/año -->
                                        <p class="text-muted">
                                        Publicado el: {{ \Carbon\Carbon::parse($publicacion->Fecha)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($publicacion->Fecha)->format('H:i:s') }}
                                        </p>
                                        <br>

                                        

                                        <!-- Botones para dar like o dislike -->
                                        <div class="d-flex justify-content-between">
                                            <form method="POST" action="{{ route('publicaciones.like', $publicacion->ID) }}" style="display:inline;">
                                                <button class="btn btn-outline-primary btn-sm like-btn" data-post-id="${publicacion.ID}">
                                                    <i class="fas fa-thumbs-up icono-verde"></i>
                                                    <span class="like-count">{{ $publicacion->reacciones->where('Tipo', 'like')->count() }}</span>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('publicaciones.dislike', $publicacion->ID) }}" style="display:inline;">
                                                <button class="btn btn-outline-secondary btn-sm dislike-btn" data-post-id="${publicacion.ID}">
                                                    <i class="fas fa-thumbs-down icono-rojo"></i>
                                                    <span class="dislike-count">{{ $publicacion->reacciones->where('Tipo', 'dislike')->count() }}</span>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Mostrar los comentarios -->
                                        <div class="mt-3">
                                            <h6>Comentarios ({{ $publicacion->comentarios->count() }})</h6>
                                            <ul>
                                                @foreach($publicacion->comentarios as $comentario)
                                                    <li>{{ $comentario->Contenido }} - <em>{{ $comentario->usuario->Usuario }}</em>  <small class="text-muted">{{ \Carbon\Carbon::parse($comentario->Fecha)->format('d/m/Y H:i:s') }}</small></li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        <!-- Formulario para agregar un comentario -->
                                        <form method="POST" action="{{ route('comentarios.store') }}">
                                            @csrf
                                            <input type="hidden" name="publicacion" value="{{ $publicacion->ID }}">
                                            <textarea name="contenido" class="form-control" placeholder="Escribe un comentario..." required></textarea>
                                            <button type="submit" class="btn btn-secondary mt-2">Comentar</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach


                            </div>
                        </div>

                        <!-- Lista de amigos -->
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Lista de Amigos</h5>
                                    <div id="friendsList" data-amigos='@json($amigos)'>
                                        <!-- Aquí se mostrarán los amigos -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                &copy; 2024 LinkedBiz. Todos los derechos reservados.
                <a class="text-white" href="https://creativecommons.org/licenses/by-nc/4.0/">Licencia Creative Commons</a>
            </div>
        </div>
    </main>


    

    <!-- Scripts -->
    <script type="module" src="../resources/js/userProfile.js" data-comentarios-store-url="{{ route('comentarios.store') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
