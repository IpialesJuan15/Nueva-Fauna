<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios'; // Tabla en la base de datos
    protected $primaryKey = 'user_id'; // Clave primaria

    protected $fillable = [
        'user_nombre',
        'user_apellido',
        'user_email',
        'password', // Este atributo debe coincidir con la columna en la BD
        'user_telefono',
        'user_estado',
        'tipus_id',
    ];

    protected $hidden = [
        'password', // Ocultar este campo en respuestas JSON
    ];

    // Mutador para encriptar la contraseña automáticamente
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // Relación con `TiposUsuario`
    public function tipoUsuario()
    {
        return $this->belongsTo(TiposUsuario::class, 'tipus_id');
    }
}
