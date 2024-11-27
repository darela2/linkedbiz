<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $table = 'comentario';
    protected $fillable = ['Publicacion', 'Usuario', 'Contenido'];
    public $incrementing = true;
    protected $primaryKey = 'ID';
    public $timestamps = false; // Deshabilitar los timestamps

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class, 'Publicacion');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'Usuario');
    }
}
