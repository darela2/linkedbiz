<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegistrarEmpresaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrarUsuarioController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SearchUsersController;
use App\Http\Controllers\PublicacionController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\ReaccionController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\NotificacionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Ruta para el home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas para el registro
Route::post('/register-company', [RegistrarEmpresaController::class, 'register'])->name('register.company');
Route::post('/register-user', [RegistrarUsuarioController::class, 'register'])->name('register.user');

// Ruta para el login
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Ruta para restablecer la contraseña
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.sendResetLink');



// Ruta para el dashboard de usuario
Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');

// Ruta para el dashboard de usuario
Route::get('/user/dashboard', [UsuarioController::class, 'showEditProfile'])->middleware('auth')->name('user.dashboard');

// Ruta para editar perfil de usuario
Route::post('/usuarios/editar', [UsuarioController::class, 'updateProfile'])->middleware('auth')->name('user.update');

// Ruta para la busqueda de usuarios
Route::get('/search-users', [SearchUsersController::class, 'index'])->name('searchUsers');
Route::post('/search-users', [SearchUsersController::class, 'search'])->name('searchUsers.search');


// Ruta para obtener la lista de amigos del usuario autenticado
Route::get('/user/{id}/friends', [FriendController::class, 'getFriends'])->middleware('auth')->name('friends.list');

// Ruta para ver el perfil de un usuario
Route::get('/user/{id}', [SearchUsersController::class, 'show'])->name('user.profile');

// Ruta para enviar una solicitud de amistad
Route::post('/user/{id}/friend-request', [SearchUsersController::class, 'sendFriendRequest'])->name('user.friendRequest');

// Rutas para publicaciones
Route::post('/publicaciones', [PublicacionController::class, 'store'])->name('publicaciones.store');

// Rutas para comentarios
Route::post('/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');


// Rutas para reacciones
Route::post('/publicaciones/{id}/like', [ReaccionController::class, 'like'])->name('publicaciones.like');
Route::post('/publicaciones/{id}/dislike', [ReaccionController::class, 'dislike'])->name('publicaciones.dislike');

// Rutas para publicaciones
Route::get('/publicaciones/{id}', [PublicacionController::class, 'index'])->name('publicaciones.index');
Route::get('/publicaciones/{id}/comentarios', [PublicacionController::class, 'getComentarios'])->name('publicaciones.getComentarios');

//Ruta para notificaciones
Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones');
Route::get('/notificaciones/{id}', [NotificacionController::class, 'getNotifications'])->name('user.notifications');

// Rutas para manejar las solicitudes de amistad
Route::post('/friend-request/{id}/accept', [NotificacionController::class, 'acceptFriendRequest']);
Route::post('/friend-request/{id}/reject', [NotificacionController::class, 'rejectFriendRequest']);

// Rutas para el dashboard del administrador
Route::prefix('admin')->group(function() {
    Route::get('/dashboard', [AdminController::class, 'showDashboard'])->name('admin.dashboard');

    // Ruta para obtener todas las empresas (método GET)
    Route::get('/empresas', [AdminController::class, 'getAllCompanies'])->name('admin.companies.index');

    // Rutas CRUD dinámicas
    Route::delete('/empresas/delete/{id}', [AdminController::class, 'deleteCompany'])->name('admin.companies.delete');
    
    Route::get('/empresas/{companyId}/usuarios', [AdminController::class, 'getUsers'])->name('admin.companies.users');
    Route::delete('/usuarios/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    Route::get('/usuarios/{userId}/publicaciones', [AdminController::class, 'getPosts'])->name('admin.users.posts');
    Route::delete('/publicaciones/{id}', [AdminController::class, 'deletePost'])->name('admin.posts.delete');

    Route::get('/estadisticas', [AdminController::class, 'getStatistics'])->name('admin.statistics');
});


