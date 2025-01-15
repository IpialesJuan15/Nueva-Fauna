<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'tipus_id',
        'user_nombre',
        'user_apellido',
        'user_email',
        'user_password',
        'user_telefono',
        'user_estado',
        'created_at',
        'updated_at',
    ];

    // Relación con TipoUsuario
    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'tipus_id', 'tipus_id');
    }
}
