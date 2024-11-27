<?php

namespace App\Http\Controllers;

use App\Models\Amigo; // Asegúrate de tener el modelo correcto
use App\Models\Usuario; // Asegúrate de tener el modelo correcto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function getFriends()
    {
        $userID = Auth::id(); // Obtiene el ID del usuario autenticado

        // Obtiene la lista de amigos con solicitudes aceptadas
        $friends = Amigo::where(function ($query) use ($userID) {
                $query->where('UsuarioEmisor', $userID)
                      ->orWhere('UsuarioReceptor', $userID);
            })
            ->where('Solicitud', 'Aceptada') // Filtra solo las solicitudes aceptadas
            ->get();

        // Crea un array para almacenar los amigos con sus nombres y URL de perfil
        $friendsList = [];

        foreach ($friends as $friend) {
            // Determina el ID del amigo
            $friendId = $friend->UsuarioEmisor == $userID ? $friend->UsuarioReceptor : $friend->UsuarioEmisor;
        
            // Obtiene el usuario amigo por su ID
            $userFriend = Usuario::find($friendId);
        
            // Si el amigo existe, agrega su nombre y URL de perfil a la lista
            if ($userFriend) {
                $friendsList[] = [
                    'id' => $userFriend->ID,
                    'nombre' => $userFriend->Nombre,
                    'apellido' => $userFriend->Apellidos,
                    'username' => $userFriend->Usuario,
                    'fotografia' => $userFriend->Fotografia,
                    'perfil_url' => route('user.profile', ['id' => $userFriend->ID]), // Ruta al perfil
                ];
            }
        }

        return response()->json($friendsList);
    }
}
