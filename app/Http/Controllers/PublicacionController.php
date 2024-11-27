<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publicacion;
use Carbon\Carbon;
use App\Models\Comentario;
use App\Models\Reaccion;

class PublicacionController extends Controller
{
    // Método para obtener las publicaciones de un usuario
    public function index($userId)
    {
        // Obtener las publicaciones del usuario
        $publicaciones = Publicacion::with(['usuario', 'comentarios.usuario','reacciones']) // Asegúrate de que las relaciones estén definidas
            ->where('Usuario', $userId)
            ->get()
            ->map(function ($publicacion) {
                // Contar la cantidad de likes y dislikes
                $likesCount = Reaccion::where('Publicacion', $publicacion->ID)->where('Tipo', 'like')->count();
                $dislikesCount = Reaccion::where('Publicacion', $publicacion->ID)->where('Tipo', 'dislike')->count();

                // Agregar la información de likes y dislikes como atributos temporales
                $publicacion->setAttribute('likes', $likesCount);
                $publicacion->setAttribute('dislikes', $dislikesCount);

                return $publicacion;
            })
            ;

        return response()->json($publicaciones);
    }


    public function getComentarios($publicacionId)
    {
        $comentarios = Comentario::with('usuario')
            ->where('Publicacion', $publicacionId)
            ->get();

        return response()->json($comentarios);
    }


    // Método para crear una nueva publicación
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'Contenido' => 'required|string',
            'Imagen' => 'nullable|image|max:2048' // Si permites imágenes opcionales
        ]);

        // Crear una nueva publicación
        $publicacion = new Publicacion();
        $publicacion->Usuario = auth()->user()->ID;
        $publicacion->Contenido = $request->Contenido;
        
        // Asignar la fecha actual
        $publicacion->Fecha = Carbon::now(); // Establecer la fecha actual

        // Manejo de la imagen, si se proporciona
        if ($request->hasFile('Imagen')) {
            $publicacion->Imagen = $request->file('Imagen')->store('public', 'public');
        }

        // Guardar la publicación en la base de datos
        $publicacion->save();

        // Preparar los datos de respuesta, incluyendo la nueva publicación
        return response()->json([
            'success' => true,
            'message' => 'Publicación creada con éxito',
            'ID' => $publicacion->ID,
            'Contenido' => $publicacion->Contenido,
            'Imagen' => $publicacion->Imagen,
            'Usuario' => $publicacion->Usuario,
            'Fecha' => $publicacion->Fecha->format('Y-m-d H:i:s'),
        ]);
    }
}

