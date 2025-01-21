<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'tipo_id',
        'user_cedula',
        'user_nombre',
        'user_apellido',
        'user_email',
        'user_password',
        'user_telefono',
        'user_estado',
    ];

    protected $hidden = [
        'user_password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'user_password' => 'hashed',
        ];
    }
}