<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    protected $table = 'tipos_usuarios';
    protected $primaryKey = 'tipus_id';

    protected $fillable = [
        'perus_id',
        'tipus_detalles',
    ];

    // Relación con PermisoUsuario
    public function permisoUsuario()
    {
        return $this->belongsTo(PermisoUsuario::class, 'perus_id', 'perus_id');
    }

    // Relación con Usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'tipus_id', 'tipus_id');
    }
}
