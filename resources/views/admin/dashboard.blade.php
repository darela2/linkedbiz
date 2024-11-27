<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LinkedBiz - Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../resources/css/admin.css">
    <link rel="icon" href="../resources/views/logo-linkedbiz.png" type="image/png">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar-bg">
        <div class="container-fluid">
            <div class="d-flex justify-content-between w-100">
                <a class="navbar-brand mx-auto" href="#">
                    <img src="../resources/views/logo-linkedbiz.png" alt="LinkedBiz Logo" class="logo">
                </a>
                <div class="navbar-right">
                    <a class="btn btn-light" href="{{ route('home') }}">Cerrar sesi&oacute;n</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main role="main">
        <h1>Hola, {{ $admin->Nombre }} {{ $admin->Apellidos }}</h1>
        <h1 class="mb-4">Bienvenid@ al Panel de Administración de LinkedBiz</h1>
        <h2>Opciones de gestión</h2>

       <div class="container my-5">
            <div id="companiesSection" class="mb-5">
                <h2>Empresas</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>CIF</th>
                            <th>Nombre</th>
                            <th>Direcci&oacute;n</th>
                            <th>Localidad</th>
                            <th>Email</th>
                            <th>Tel&eacute;fono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="companiesTableBody">
                        <!-- Las filas de empresas se cargarán dinámicamente aquí -->
                    </tbody>
                </table>
                
                
                
            </div>

            <!-- Sección de Usuarios (oculta inicialmente) -->
            <div id="usersSection" class="mb-5" style="display: none;">
                <h2>Usuarios de la Empresa</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Biografia</th>
                            <th>Usuario</th>
                            <th>Contrase&ntilde;a</th>
                            <th>Email</th>
                            <th>Fecha Nacimiento</th>
                            <th>Fotograf&iacute;a</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <!-- Las filas de usuarios se insertarán aquí dinámicamente -->
                    </tbody>
                </table>
                <button id="backToCompanies" class="btn btn-secondary mt-2">Volver a Empresas</button>
            </div>

            <!-- Sección de publicaciones (oculta por defecto) -->
            <div id="postsSection" style="display: none;">
                <h3>Publicaciones del Usuario</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Contenido</th>
                            <th>Imagen</th>
                            <th>Fecha</th>
                            <th>Likes</th>
                            <th>Dislikes</th>
                            <th>Comentarios</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="postsTableBody">
                        <!-- Las filas de publicaciones se insertarán aquí dinámicamente -->
                    </tbody>
                </table>
                <button id="backToUsers" class="btn btn-secondary">Volver a Usuarios</button>
            </div>

            <!-- Sección de estadísticas -->
            <div id="statisticsSection">
                <h3>Estadísticas de LinkedBiz</h3>
                <!-- Contenido de estadísticas -->
                <p id="companiesCount"></p>
                <p id="usersCount"></p>
                <p id="postsCount"></p>
            </div>

            <!-- Modal de confirmación -->
            <div id="confirmModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <h4>Confirmar Eliminación</h4>
                    <p id="confirmMessage"></p>
                    <button id="confirmDeleteBtn" class="btn btn-danger">Eliminar</button>
                    <button id="cancelDeleteBtn" class="btn btn-secondary">Cancelar</button>
                </div>
            </div>


        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="container d-flex flex-column justify-content-center align-items-center pt-4">
                <section class="mb-4">
                    <a class="btn btn-link btn-floating btn-lg text-white m-1" href="https://www.facebook.com" role="button"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-link btn-floating btn-lg text-white m-1" href="https://x.com" role="button"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-link btn-floating btn-lg text-white m-1" href="https://www.tiktok.com" role="button"><i class="fab fa-tiktok"></i></a>
                    <a class="btn btn-link btn-floating btn-lg text-white m-1" href="https://www.instagram.com" role="button"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-link btn-floating btn-lg text-white m-1" href="https://es.linkedin.com" role="button"><i class="fab fa-linkedin"></i></a>
                    <a class="btn btn-link btn-floating btn-lg text-white m-1" href="https://www.github.com" role="button"><i class="fab fa-github"></i></a>
                </section>
            </div>
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                &copy; 2024 LinkedBiz. Todos los derechos reservados.
                <a class="text-white" href="https://creativecommons.org/licenses/by-nc/4.0/">Licencia Creative Commons</a>
            </div>
        </div>
    </main>
    
    
    <!-- Scripts -->
    <script type="module" src="../resources/js/admin.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
