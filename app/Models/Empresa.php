<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresa';
    protected $fillable = ['Nombre', 'Direccion', 'Localidad','Email','Telefono'];
    public $incrementing = false;
    protected $primaryKey = 'CIF';
    public $timestamps = false; // Deshabilitar los timestamps

    // Relación con Usuario
    public function users()
    {
        return $this->hasMany(Usuario::class, 'Empresa');
    }
}
