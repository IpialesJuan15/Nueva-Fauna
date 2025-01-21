<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    protected $table = 'revisiones';
    
    protected $fillable = [
        'esp_id',
        'estado',
        'comentario',
        'user_id',
        'taxonomo_id'
    ];

    // Relación con Especie
    public function especie()
    {
        return $this->belongsTo(Especie::class, 'esp_id', 'esp_id');
    }

    // Relación con Usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relación con Taxónomo
    public function taxonomo()
    {
        return $this->belongsTo(User::class, 'taxonomo_id', 'user_id');
    }
}