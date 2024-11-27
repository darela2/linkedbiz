<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Verificar si el usuario existe
        $user = DB::table('usuario')->where('Email', $request->email)->first();
        
        if (!$user) {
            return response()->json(['message' => 'No existe ningún usuario con ese correo.'], 404);
        }

        // Generar una nueva contraseña aleatoria
        $newPassword = Str::random(10); // Cambia el número según la longitud deseada

        // Actualizar la contraseña en la base de datos
        DB::table('usuario')->where('Email', $request->email)->update(['Contraseña' => $newPassword]);

        // Enviar el correo electrónico con la nueva contraseña
        Mail::send([], [], function ($message) use ($request, $newPassword) {
            $message->to($request->email)
                    ->subject('Restablecimiento de Contraseña')
                    ->html("Tu nueva contraseña es: <strong>{$newPassword}</strong>", 'text/html');
        });

        return response()->json(['message' => 'Se ha enviado un correo con tu nueva contraseña.'], 200);
    }
}
