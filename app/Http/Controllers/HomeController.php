<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class HomeController extends Controller
{
    public function index()
    {
        // Obtiene todas las empresas de la base de datos
        $empresas = Empresa::all();
        
        // Devuelve la vista 'home' y pasa las empresas
        return view('home', compact('empresas'));
    }
}
