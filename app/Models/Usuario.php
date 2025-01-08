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
        'user_nombre',
        'user_apellido',
        'user_email',
        'user_password',
        'user_telefono',
        'user_estado',
        'user_rol',
    ];

    protected $hidden = [
        'user_password',
    ];

    /**
     * Mutador para cifrar la contraseña automáticamente
     */
    public function setUserPasswordAttribute($value)
    {
        $this->attributes['user_password'] = bcrypt($value);
    }
}
