<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publicacion;
use App\Models\Reaccion; // Asegúrate de tener un modelo Reaccion
use App\Models\Notificacion;

class ReaccionController extends Controller
{
    public function like($id)
    {
        $publicacion = Publicacion::findOrFail($id);

        // Verifica si el usuario ya ha reaccionado
        $existingReaction = Reaccion::where('Publicacion', $id)
                                     ->where('Usuario', auth()->user()->ID)
                                     ->first();

        if ($existingReaction) {
            return response()->json(['success' => false, 'message' => 'Ya has reaccionado a esta publicación']);
        }

        // Crear una nueva reacción de tipo "me gusta"
        $reaction = new Reaccion();
        $reaction->Publicacion = $id;
        $reaction->Usuario = auth()->user()->ID; // Suponiendo que tienes el ID del usuario
        $reaction->Tipo = 'like'; // O 'me gusta'
        $reaction->save();

        // Crear la notificación
        $notificacion = new Notificacion();
        $notificacion->UsuarioEmisor = auth()->user()->ID; // ID del usuario que creó la publicación
        $notificacion->UsuarioReceptor = $publicacion->Usuario;
        $notificacion->Tipo = 'Me Gusta en publicacion';
        $notificacion->Mensaje = 'El usuario ' . auth()->user()->Usuario. ' ha dado like a tu publicación.';
        $notificacion->save();

        // Contar la cantidad de likes y dislikes
        $likesCount = Reaccion::where('Publicacion', $id)->where('Tipo', 'like')->count();
        $dislikesCount = Reaccion::where('Publicacion', $id)->where('Tipo', 'dislike')->count();

        return response()->json([
            'success' => true,
            'message' => 'Has dado me gusta a la publicación, notificación enviada.',
            'likes' => $likesCount,
            'dislikes' => $dislikesCount,
        ]);
    }

    public function dislike($id)
    {
        $publicacion = Publicacion::findOrFail($id);

        // Verifica si el usuario ya ha reaccionado
        $existingReaction = Reaccion::where('Publicacion', $id)
                                     ->where('Usuario', auth()->user()->ID)
                                     ->first();

        if ($existingReaction) {
            return response()->json(['success' => false, 'message' => 'Ya has reaccionado a esta publicación']);
        }

        // Crear una nueva reacción de tipo "no me gusta"
        $reaction = new Reaccion();
        $reaction->Publicacion = $id;
        $reaction->Usuario = auth()->user()->ID;
        $reaction->Tipo = 'dislike'; // O 'no me gusta'
        $reaction->save();

        // Crear la notificación
        $notificacion = new Notificacion();
        $notificacion->UsuarioEmisor = auth()->user()->ID; // ID del usuario que creó la publicación
        $notificacion->UsuarioReceptor = $publicacion->Usuario;
        $notificacion->Tipo = 'No Me Gusta en publicacion';
        $notificacion->Mensaje = 'El usuario ' . auth()->user()->Usuario . ' ha dado dislike a tu publicación.';
        $notificacion->save();

        // Contar la cantidad de likes y dislikes
        $likesCount = Reaccion::where('Publicacion', $id)->where('Tipo', 'like')->count();
        $dislikesCount = Reaccion::where('Publicacion', $id)->where('Tipo', 'dislike')->count();

        return response()->json([
            'success' => true,
            'message' => 'Has dado no me gusta a la publicación',
            'likes' => $likesCount,
            'dislikes' => $dislikesCount,
        ]);
    }
}
