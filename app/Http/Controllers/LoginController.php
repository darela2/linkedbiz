<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Administrador;
use App\Models\Usuario;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validación de credenciales
        $credentials = [
            'Usuario' => $request->input('loginName'), 
            'Contraseña' => $request->input('loginPassword'), 
        ];

        // Primero, intenta autenticar como administrador
        $admin = Administrador::where('Usuario', $credentials['Usuario'])
            ->where('Contraseña', $credentials['Contraseña'])
            ->first();

        if ($admin) {
            Auth::login($admin); // Autentica al administrador
            return response()->json([
                'success' => true,
                'role' => 'admin',
                'user' => [
                    'Usuario' => $admin->Usuario,
                    'Contraseña' => $admin->Contraseña,
                    'Nombre' => $admin->Nombre,
                    'Apellidos' => $admin->Apellidos
            ]
            ]);
        }

        // Si no se encontró un administrador, intenta autenticar como usuario
        $usuario = Usuario::where('Usuario', $credentials['Usuario'])
            ->where('Contraseña', $credentials['Contraseña'])
            ->first();

        if ($usuario) {
            Auth::login($usuario); // Autentica al usuario

            // Obtener el nombre de la empresa si existe la relación
            $nombreEmpresa = $usuario->empresa ? $usuario->empresa->Nombre : 'Sin empresa';

            return response()->json([
                'success' => true,
                'role' => 'user',
                'user' => [
                    'Usuario' => $usuario->Usuario,
                    'Contraseña' => $usuario->Contraseña,
                    'Nombre' => $usuario->Nombre,
                    'Apellidos' => $usuario->Apellidos,
                    'Biografia' => $usuario->Biografia,
                    'Email' => $usuario->Email,
                    'FechaNacimiento' => $usuario->FechaNacimiento,
                    'Fotografia' => $usuario->Fotografia,
                    'Empresa' => $nombreEmpresa // Enviar el nombre de la empresa en lugar del CIF
                ]
            ]);
        }

        // Si la autenticación falla, devolver el error
        return response()->json([
            'success' => false,
            'message' => 'Credenciales incorrectas.',
        ], 401);
    }
}
?>
