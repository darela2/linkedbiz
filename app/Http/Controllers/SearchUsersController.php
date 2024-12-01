<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario; // Asegúrate de tener este modelo
use App\Models\Publicacion; // Asegúrate de tener este modelo de publicaciones
use App\Models\Amigo; // Asegúrate de tener este modelo de amistades
use App\Models\Notificacion; // Asegúrate de tener este modelo de notificaciones
use Illuminate\Support\Facades\DB;


class SearchUsersController extends Controller
{
    // Muestra la página de búsqueda de usuarios
    public function index()
    {
        // Renderiza la vista de búsqueda de usuarios
        return view('user/searchUsers');
    }

    // Realiza la búsqueda de usuarios
    public function search(Request $request)
    {
        // Validar el input de búsqueda
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        // Obtener el ID del usuario autenticado
        $currentUserId = auth()->user()->ID;

        // Buscar usuarios cuyo nombre, apellidos, o nombre de usuario coincidan con la consulta
        // y excluir al usuario actualmente autenticado
        $query = $request->input('query');
        $usuarios = Usuario::where('ID', '!=', $currentUserId) // Excluir al usuario autenticado
            ->where(function($queryBuilder) use ($query) {
                $queryBuilder->where('Nombre', 'like', "%$query%")
                    ->orWhere('Apellidos', 'like', "%$query%")
                    ->orWhere('Usuario', 'like', "%$query%");
            })
            ->get();

        // Retornar los resultados de la búsqueda como JSON para manejarlo con JavaScript en la vista
        return response()->json($usuarios);
    }


    // Muestra el perfil de un usuario específico y sus publicaciones
    public function show($id)
    {
        // Obtener el usuario por su ID
        $usuario = Usuario::with('empresa')->find($id); // Asegúrate de cargar la relación con la empresa

        // Obtener las publicaciones del usuario
        $publicaciones = Publicacion::where('Usuario', $id)->with(['comentarios', 'reacciones'])->orderBy('Fecha', 'desc')->get();

        // Obtener los amigos del usuario, tanto como emisor como receptor de la solicitud aceptada
        $amigos = DB::table('amigo')
        ->join('usuario', function ($join) use ($id) {
            $join->on('amigo.UsuarioEmisor', '=', 'usuario.ID')
                ->orOn('amigo.UsuarioReceptor', '=', 'usuario.ID');
        })
        ->where(function ($query) use ($id) {
            $query->where('amigo.UsuarioEmisor', $id)
                ->orWhere('amigo.UsuarioReceptor', $id);
        })
        ->where('amigo.Solicitud', 'aceptada')
        ->where('usuario.ID', '!=', $id) // Excluir al usuario actual
        ->select('usuario.ID', 'usuario.Usuario', 'usuario.Nombre', 'usuario.Apellidos', 'usuario.Fotografia')
        ->get();

        // Verificar si el usuario tiene una fotografia
        if ($usuario->Fotografia) {
            $usuario->Fotografia = '/proyectofinal-linkedbiz/storage/app/app/' . $usuario->Fotografia;
        } else if($usuario->Fotografia == NULL) {
            $usuario->Fotografia = 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg';
        }

        // Convertir la fecha de nacimiento al formato (día/mes/año) usando Carbon
        $usuario->FechaNacimiento = \Carbon\Carbon::parse($usuario->FechaNacimiento)->format('d/m/Y');

        
        // Devolver la vista con el perfil del usuario y sus publicaciones
        return view('user.profile', compact('usuario', 'publicaciones','id', 'amigos'));
    }

    // Envía una solicitud de amistad a un usuario
    public function sendFriendRequest(Request $request, $id)
    {
        // Obtener el ID del usuario autenticado
        $senderId = auth()->user()->ID;

        // Verificar si ya existe una solicitud de amistad pendiente entre los usuarios
        $existingRequest = Amigo::where(function ($query) use ($senderId, $id) {
            $query->where('UsuarioEmisor', $senderId)
                ->where('UsuarioReceptor', $id);
        })->orWhere(function ($query) use ($senderId, $id) {
            $query->where('UsuarioEmisor', $id)
                ->where('UsuarioReceptor', $senderId);
        })->first();

        if ($existingRequest) {
            return response()->json(['message' => 'Ya has enviado una solicitud de amistad a este usuario.'], 400);
        }

        // Crear una nueva solicitud de amistad
        $friendRequest = new Amigo();
        $friendRequest->UsuarioEmisor = $senderId; // El ID del usuario que envía la solicitud
        $friendRequest->UsuarioReceptor = $id; // El ID del usuario que recibe la solicitud
        $friendRequest->Solicitud = 'Pendiente'; // Estado de la solicitud

        // Guardar la solicitud
        $friendRequest->save();


         // Crear la notificación
        $notificacion = new Notificacion();
        $notificacion->UsuarioReceptor = $friendRequest->UsuarioReceptor; // ID del usuario receptor
        $notificacion->UsuarioEmisor = $friendRequest->UsuarioEmisor;
        $notificacion->Tipo = 'Solicitud de amistad';
        $notificacion->Mensaje = 'El usuario ' . auth()->user()->Usuario . ' te ha enviado una solicitud de amistad.';
        $notificacion->save();

        return response()->json(['message' => 'Solicitud de amistad y notificación enviadas correctamente.']);
    }

}
