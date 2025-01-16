<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermisoUsuario extends Model
{
    protected $table = 'permisos_usuarios';
    protected $primaryKey = 'perus_id';

    protected $fillable = ['perus_detalle'];

    public $timestamps = false;

    public function tiposUsuarios()
    {
        return $this->hasMany(TipoUsuario::class, 'perus_id', 'perus_id');
    }
}
