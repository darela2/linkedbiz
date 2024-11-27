<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Empresa;

class UsuarioController extends Controller
{
    public function showEditProfile()
    {
        // Obtén el usuario por su ID
        $user = auth()->user();

        // Obtén todas las empresas registradas
        $empresas = Empresa::all();

        // Devuelve la vista y pasa las variables
        return view('user.dashboard', compact('user', 'empresas'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user(); // Obtén el usuario autenticado
    
        $validatedData = $request->validate([
            'Nombre' => 'required|string|max:255',
            'Apellidos' => 'required|string|max:255',
            'Biografia' => 'required|string',
            'Email' => 'required|email|max:255|unique:usuario,Email,' . $user->ID,
            'FechaNacimiento' => 'required|date',
            'Fotografia' => 'nullable|image|max:2048',
            'Empresa' => 'required|exists:empresa,CIF', // Verifica que la empresa exista en la tabla
        ]);
    
        $user->fill($validatedData);
    
        if ($request->hasFile('Fotografia')) {
            $user->Fotografia = $request->file('Fotografia')->store('fotografias', 'public');
        }
    
        $user->save();
        
        // Devolver un JSON con todos los datos del usuario actualizados
        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado correctamente.',
            'user' => $user,
        ]);
    }
    
}
