<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amigo extends Model
{
    use HasFactory;

    protected $table = 'amigo';
    protected $fillable = ['UsuarioEmisor', 'UsuarioReceptor', 'Solicitud'];
    public $incrementing = true;
    protected $primaryKey = 'ID';
    public $timestamps = false; // Deshabilitar los timestamps
}
