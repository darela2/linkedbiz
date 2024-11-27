<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Cambia esto
use Illuminate\Notifications\Notifiable; // Asegúrate de incluir esto también

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable; // Asegúrate de incluir el trait Notifiable
    
    protected $table = 'usuario';
    protected $fillable = ['Nombre', 'Apellidos', 'Biografia', 'Usuario', 'Contraseña', 'Email', 'FechaNacimiento', 'Fotografia', 'Empresa'];
    public $incrementing = true;
    protected $primaryKey = 'ID';
    public $timestamps = false; // Deshabilitar los timestamps

    // Relación con Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'Empresa','CIF');
    }

     // Relación con Publicacion
    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'Usuario', 'ID');
    }

    // Relación con Amigo
    public function amigos()
    {
        return $this->belongsToMany(Usuario::class, 'amigo', 'UsuarioEmisor', 'UsuarioReceptor')
                    ->withPivot('Solicitud')
                    ->wherePivot('Solicitud', 'aceptada');
    }  

    // Relación con Reaccion
    public function reacciones()
    {
        return $this->hasMany(Reaccion::class, 'Usuario');
    }

    // Relación con Comentario
    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'Usuario');
    }

}
