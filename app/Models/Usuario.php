<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'tipus_id', // Relación con tipos de usuario
        'user_nombre',
        'user_apellido',
        'user_email',
        'user_password',
        'user_telefono',
        'user_estado',
    ];

    protected $hidden = [
        'user_password',
    ];

    /**
     * Cifra la contraseña automáticamente antes de guardarla
     */
    public function setUserPasswordAttribute($value)
    {
        $this->attributes['user_password'] = bcrypt($value);
    }

    /**
     * Relación con `tipos_usuarios`
     */
    public function tiposUsuario()
    {
        return $this->belongsTo(TiposUsuario::class, 'tipus_id');
    }
}
