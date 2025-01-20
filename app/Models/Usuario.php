<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios'; // Nombre de la tabla
    protected $primaryKey = 'user_id'; // Clave primaria

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

    protected $hidden = ['user_password']; // Ocultar la contraseña al serializar

    public $timestamps = true;

    // Mutador para cifrar automáticamente la contraseña
    //public function setUserPasswordAttribute($value)
    //{
      //  $this->attributes['user_password'] = Hash::make($value);
    //}

    // Relación con TipoUsuario
    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'tipus_id', 'tipus_id');
    }
}
