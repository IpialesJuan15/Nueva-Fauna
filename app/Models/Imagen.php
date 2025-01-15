<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    protected $table = 'tax_imagenes';
    protected $primaryKey = 'img_id';

    protected $fillable = [
        'img_esp_id',
        'img_ruta',
        'img_descripcion',
        'created_at',
        'updated_at',
    ];

    public function especie()
    {
        return $this->belongsTo(Especie::class, 'img_esp_id', 'esp_id');
    }
}
