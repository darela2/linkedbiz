<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaccion extends Model
{
    use HasFactory;

    protected $table = 'reaccion';
    protected $fillable = ['Publicacion', 'Usuario', 'Tipo'];
    public $incrementing = true;
    protected $primaryKey = 'ID';
    public $timestamps = false; // Deshabilitar los timestamps

    public function scopeLikes($query)
    {
        return $query->where('Tipo', 'like');
    }

    public function scopeDislikes($query)
    {
        return $query->where('Tipo', 'dislike');
    }
}
