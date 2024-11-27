<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comentario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Notificacion;

class ComentarioController extends Controller
{
    public function store(Request $request)
    {
        $comentario = Comentario::find($request->Publicacion);

        // Verificar el contenido del request
        Log::info('Datos recibidos:', $request->all());
        
        $request->validate([
            'Publicacion' => 'required|exists:publicacion,ID',
            'Contenido' => 'required|string'
        ]);
        

        $comentario = new Comentario();
        $comentario->Usuario = auth()->user()->ID;
        $comentario->Publicacion = $request->Publicacion;
        $comentario->Contenido = $request->Contenido;
        
        // Asignar la fecha actual
        $comentario->Fecha = Carbon::now(); // Establecer la fecha actual

        $comentario->save();

        // Crear la notificación
        $notificacion = new Notificacion();
        $notificacion->UsuarioEmisor = auth()->user()->ID; // ID del usuario que creó la publicación
        $notificacion->UsuarioReceptor = $comentario->publicacion->usuario->ID;
        $notificacion->Tipo = 'Comentario en publicación';
        $notificacion->Mensaje = 'El usuario ' . auth()->user()->Usuario . ' ha comentado en tu publicación.';
        $notificacion->save();

        return response()->json([
            'success' => true, 
            'message' => 'Comentario creado con éxito y notificación enviada',
            'ID' => $comentario->ID,
            'NombreUsuario' => auth()->user()->Usuario, // Agrega el nombre de usuario
            'Contenido' => $comentario->Contenido,
            'Fecha'=> $comentario->Fecha->format('d/m/Y H:i:s'),
            'usuario' => $comentario->publicacion->usuario
            
        ]);
    }
}
