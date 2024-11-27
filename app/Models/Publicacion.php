<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    use HasFactory;

    protected $table = 'publicacion';
    protected $fillable = ['Usuario', 'Contenido', 'Imagen', 'Fecha'];
    public $incrementing = true;
    protected $primaryKey = 'ID';
    public $timestamps = false; // Deshabilitar los timestamps

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'Usuario');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'Publicacion');
    }

    public function reacciones()
    {
        return $this->hasMany(Reaccion::class, 'Publicacion');
    }
}
