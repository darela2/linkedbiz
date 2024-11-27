<?php

namespace App\Http\Controllers;

use App\Models\Empresa; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrarEmpresaController extends Controller
{

    /**
     * Registra una nueva empresa.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'companyID' => 'required|string|max:255|unique:empresa,CIF',
            'companyName' => 'required|string|max:255',
            'companyLocation' => 'nullable|string|max:255',
            'companyCity' => 'nullable|string|max:255',
            'companyEmail' => 'nullable|string|email|max:255',
            'companyPhone' => 'nullable|numeric',
        ]);
        
        // Si la validación falla, muestra mensaje JSON de error
        if ($validator->fails()) {
            response()->json(['success' => false, 'message' => 'Los datos introducidos no son los adecuados.']);
        }

        // Crea una nueva instancia de la empresa
        $empresa = new Empresa();
        $empresa->CIF = $request->companyID;
        $empresa->Nombre = $request->companyName;
        $empresa->Direccion = $request->companyLocation;
        $empresa->Localidad = $request->companyCity;
        $empresa->Email = $request->companyEmail;
        $empresa->Telefono = $request->companyPhone;
        // Guarda la empresa en la base de datos
        $empresa->save();

        //Devuelve respuesta JSON
        return response()->json(['success' => true, 'message' => 'Registro exitoso']);
    }
}
