<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Cambia esto
use Illuminate\Notifications\Notifiable; // Asegúrate de incluir esto también


class Administrador extends Authenticatable
{
    use HasFactory;
    
    protected $table = 'administrador'; 
    protected $fillable = ['Nombre', 'Apellidos', 'Usuario', 'Contraseña'];
    public $incrementing = true; // Para utilizar IDs auto-incrementales
    protected $primaryKey = 'ID'; // Especifica el campo ID como clave primaria
    public $timestamps = false; // Deshabilitar los timestamps
}
