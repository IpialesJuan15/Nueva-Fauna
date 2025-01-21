<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'tipus_id',
        'user_cedula',
        'user_nombre',
        'user_apellido',
        'user_email',
        'user_password',
        'user_telefono',
        'user_estado'
    ];

    protected $hidden = [
        'user_password',
        'remember_token',
    ];

    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'tipus_id', 'tipus_id');
    }

    // Métodos necesarios para la autenticación
    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function getAuthIdentifierName()
    {
        return 'user_id';
    }
}