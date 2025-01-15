<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios'; // Nombre de la tabla
    protected $primaryKey = 'user_id'; // Clave primaria

    // Columnas que pueden ser asignadas en masa
    protected $fillable = [
        'tipus_id',
        'user_nombre',
        'user_apellido',
        'user_email',
        'user_password',
        'user_telefono',
        'user_estado',
    ];

    // Ocultar el password al serializar el modelo
    protected $hidden = [
        'user_password',
    ];

    // Mutadores para hash de contraseñas
    public function setUserPasswordAttribute($value)
    {
        $this->attributes['user_password'] = bcrypt($value);
    }
}
