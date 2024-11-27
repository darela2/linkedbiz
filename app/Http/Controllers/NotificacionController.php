<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notificacion;
use Carbon\Carbon;
use App\Models\Amigo;


class NotificacionController extends Controller
{

    public function index()
    {
        return view('user/notificaciones');
    }
    public function getNotifications($id)
    {
        // Obtener las notificaciones del usuario por su ID, ordenadas por fecha
        $notificaciones = Notificacion::where('UsuarioReceptor', $id)
            ->orderBy('Fecha', 'desc')
            ->get(['ID', 'Tipo', 'UsuarioEmisor' , 'Mensaje', 'Fecha']);


        // Transformar las notificaciones para personalizar la respuesta
        $notificaciones = $notificaciones->map(function ($notificacion) {
            $notificacionArray = $notificacion->toArray();
            $notificacionArray['Fecha'] = Carbon::parse($notificacionArray['Fecha'])->format('d-m-Y H:i:s'); // Formato deseado
            return $notificacionArray;
        });

        // Devolver las notificaciones en formato JSON
        return response()->json($notificaciones);
    }

    public function acceptFriendRequest($id)
    {
        // Supongamos que el $id es el ID del usuario que envió la solicitud de amistad
        $userId = auth()->id(); // El ID del usuario que acepta la solicitud
        $friendRequest = Amigo::where('UsuarioEmisor', intval($id))
            ->where('UsuarioReceptor', $userId)
            ->where('Solicitud', 'Pendiente')
            ->first();

        if ($friendRequest) {
            // Cambiar el estado de la solicitud a Aceptada
            $friendRequest->Solicitud = 'Aceptada';
            $friendRequest->save();

            // Crear la notificación para el emisor de la solicitud
            Notificacion::create([
                'Tipo' => 'Solicitud de Amistad Aceptada',
                'UsuarioEmisor' => $userId, // El usuario que envió la solicitud
                'UsuarioReceptor' => intval($id),
                'Mensaje' => 'Tu solicitud de amistad ha sido aceptada.',
                'Fecha' => Carbon::now(), // Fecha y hora actual
            ]);

            // Borrar la antigua notificación
            Notificacion::where('Tipo', 'Solicitud de amistad')
                ->where('UsuarioEmisor', intval($id))
                ->where('UsuarioReceptor', $userId)
                ->delete();

            return response()->json(['message' => 'Solicitud de amistad aceptada.']);
        }

        return response()->json(['message' => 'No se encontró la solicitud de amistad.'], 404);
    }

    public function rejectFriendRequest($id)
    {
        // Supongamos que el $id es el ID del usuario que envió la solicitud de amistad
        $userId = auth()->id(); // El ID del usuario que acepta la solicitud
        $friendRequest = Amigo::where('UsuarioEmisor', intval($id))
            ->where('UsuarioReceptor', $userId)
            ->where('Solicitud', 'Pendiente')
            ->first();

            

        if ($friendRequest) {
            // Cambiar el estado de la solicitud a Rechazada
            $friendRequest->Solicitud = 'Rechazada';
            $friendRequest->save();

            // Crear la notificación para el emisor de la solicitud
            Notificacion::create([
                'Tipo' => 'Solicitud de Amistad Rechazada',
                'UsuarioEmisor' => $userId, // El usuario que envió la solicitud
                'UsuarioReceptor' => intval($id),
                'Mensaje' => 'Tu solicitud de amistad ha sido rechazada.',
                'Fecha' => Carbon::now(), // Fecha y hora actual
            ]);

            // Borrar la antigua notificación
            Notificacion::where('Tipo', 'Solicitud de amistad')
                ->where('UsuarioEmisor', intval($id))
                ->where('UsuarioReceptor', $userId)
                ->delete();

            return response()->json(['message' => 'Solicitud de amistad rechazada.']);
        }

        return response()->json(['message' => 'No se encontró la solicitud de amistad.'], 404);
    }


}
