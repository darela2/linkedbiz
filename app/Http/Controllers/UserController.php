<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Aquí puedes agregar la lógica para mostrar el dashboard del usuario
        return view('user.dashboard'); // Asegúrate de tener una vista en resources/views/user/dashboard.blade.php
    }
}
?>
