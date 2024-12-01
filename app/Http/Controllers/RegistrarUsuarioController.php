<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; // Para encriptar la contraseña

class RegistrarUsuarioController extends Controller
{
    /**
     * Registra un nuevo usuario en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'userName' => 'required|string|max:255',
            'userSurname' => 'required|string|max:255',
            'userBiography' => 'nullable|string',
            'user' => 'required|string|max:255',
            'userPassword' => 'required|string|max:255', 
            'userEmail' => 'required|string|email|max:255',
            'userBirthDate' => ['required', 'date', function ($attribute, $value,$fail) {
                $birthDate = Carbon::parse($value);
                if ($birthDate->diffInYears(Carbon::now()) < 16) {
                    $fail('Debes tener al menos 16 años para registrarte.');
                }
            }],
            'userPhoto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Opcional si no hay foto
            'companyCIF' => 'required|exists:empresa,cif', // Validar que la empresa existe
        ]);

        // Si la validación falla, devolvemos una respuesta JSON con los errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() // Devolver el primer mensaje de error
            ]);
        }

        
        // Crear y guardar el nuevo usuario
        $usuario = new Usuario();
        $usuario->Nombre = $request->input('userName');
        $usuario->Apellidos = $request->input('userSurname');
        $usuario->Biografia = $request->input('userBiography');
        $usuario->Usuario = $request->input('user');
        $usuario->Email = $request->input('userEmail');
        $usuario->Contraseña = $request->input('userPassword');  
        $usuario->FechaNacimiento = $request->input('userBirthDate');
        $usuario->Empresa = $request->input('companyCIF'); // Asigna la empresa seleccionada
        

        // Si tiene foto de perfil
        if ($request->hasFile('userPhoto')) {
            $file = $request->file('userPhoto');
            $path = $file->store('app/'); // Guardar la foto directamente en el directorio 'storage/app'
            $usuario->Fotografia = basename($path); // Almacenar solo el nombre del archivo
        }

        //Guarda el usuario en la base de datos
        $usuario->save();

        //Devuelve respuesta JSON
        return response()->json(['success' => true, 'message' => 'Registro exitoso']);
    }
}
