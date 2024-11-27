<?php
namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Publicacion;
use App\Models\Amigo;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Renderiza la vista del dashboard de admin
    public function showDashboard()
    {
        $companies = Empresa::all();
        $admin = Administrador::all()->first();
        return view('admin.dashboard', compact('companies', 'admin'));
    }

    public function getAllCompanies()
    {
        $companies = Empresa::all();
        return response()->json($companies);
    }





    public function deleteCompany($id)
    {
        $empresa = Empresa::find($id);

        if ($empresa) {
            // Eliminar usuarios vinculados a la empresa
            $usuarios = Usuario::where('Empresa', $id)->get();
            foreach ($usuarios as $usuario) {
                $this->deleteUser($usuario->ID); // Llama a la función deleteUser para cada usuario
            }

            // Finalmente, eliminar la empresa
            $empresa->delete();

            return response()->json(['message' => 'Empresa y sus usuarios eliminados']);
        }

        return response()->json(['message' => 'Empresa no encontrada'], 404);
    }

    // Obtener usuarios de una empresa específica
    public function getUsers($companyId)
    {
        $users = Usuario::where('Empresa', $companyId)->get();
        // Convertir la fecha de nacimiento al formato adecuado en Carbon (d/m/Y)
        foreach ($users as $user) {
            $user->FechaNacimiento = Carbon::parse($user->FechaNacimiento)->format('d/m/Y');
            
        }
        return response()->json($users);
    }


    public function deleteUser($id)
    {
        $usuario = Usuario::find($id);

        if ($usuario) {
            // Eliminar publicaciones del usuario
            $publicaciones = Publicacion::where('Usuario', $id)->get();
            foreach ($publicaciones as $publicacion) {
                $this->deletePost($publicacion->id); // Llama a la función deletePost para cada publicación
            }

            // Eliminar solicitudes de amistad
            Amigo::where('UsuarioEmisor', $id)->orWhere('UsuarioReceptor', $id)->delete();

            // Eliminar Notificaciones
            Notificacion::where('UsuarioEmisor', $id)->orWhere('UsuarioReceptor', $id)->delete();

            // Finalmente, eliminar el usuario
            $usuario->delete();

            return response()->json(['message' => 'Usuario y sus relaciones eliminados']);
        }

        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    // Obtener publicaciones de un usuario específico
    public function getPosts($userId)
    {
        $posts = Publicacion::where('Usuario', $userId)
        ->withCount([
            'comentarios', // Cuenta los comentarios
            'reacciones as likes_count' => function ($query) {
                $query->likes(); // Filtra y cuenta las reacciones tipo "like"
            },
            'reacciones as dislikes_count' => function ($query) {
                $query->dislikes(); // Filtra y cuenta las reacciones tipo "dislike"
            },
        ])
        ->get();
        
        // Convertir la fecha de nacimiento al formato adecuado en Carbon (d/m/Y a las H:i:s)
        foreach ($posts as $post) {
            $post->Fecha = Carbon::parse($post->Fecha)->format('d/m/Y H:i:s');
        }
        return response()->json($posts);
    }


    public function deletePost($id)
    {
        $publicacion = Publicacion::find($id);

        if ($publicacion) {
            // Eliminar comentarios de la publicación
            $publicacion->comentarios()->delete();

            // Eliminar reacciones de la publicación
            $publicacion->reacciones()->delete();

            // Finalmente, eliminar la publicación
            $publicacion->delete();

            return response()->json(['message' => 'Publicación, comentarios y reacciones eliminados']);
        }

        return response()->json(['message' => 'Publicación no encontrada'], 404);
    }

    // Obtener estadísticas
    public function getStatistics()
    {
        $statistics = [
            'totalCompanies' => Empresa::count(),
            'totalUsers' => Usuario::count(),
            'totalPosts' => Publicacion::count(),
        ];
        return response()->json($statistics);
    }
}
