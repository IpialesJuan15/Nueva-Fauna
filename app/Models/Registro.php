<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $table = 'tax_registros';
    protected $primaryKey = 'regis_id';

    protected $fillable = [
        'esp_id',
        'user_id',
        'regis_estado',
        'created_at',
        'updated_at',
    ];

    public function especie()
    {
        return $this->belongsTo(Especie::class, 'esp_id', 'esp_id');
    }

    public function validaciones()
    {
        return $this->hasMany(Validacion::class, 'valid_regis_id', 'regis_id');
    }
}
