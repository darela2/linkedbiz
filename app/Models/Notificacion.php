<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificacion';
    protected $fillable = ['Tipo', 'Fecha', 'UsuarioEmisor', 'UsuarioReceptor', 'Mensaje'];
    public $incrementing = true;
    protected $primaryKey = 'ID';
    protected $nullable = ['UsuarioEmisor'];
    public $timestamps = false; // Deshabilitar los timestamps

    protected $casts = [
        'Fecha' => 'datetime',
    ];

    // Relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'Usuario', 'ID'); // 'Usuario' en Notificacion se relaciona con 'ID' en Usuario
    }
}
