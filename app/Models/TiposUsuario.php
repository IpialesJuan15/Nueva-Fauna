<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposUsuario extends Model
{
    use HasFactory;

    protected $table = 'tipos_usuarios';
    protected $primaryKey = 'tipus_id';

    protected $fillable = [
        'tipus_detalles',
    ];

    /**
     * Relación con `usuarios`
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'tipus_id');
    }
}
