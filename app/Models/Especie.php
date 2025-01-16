<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    protected $table = 'tax_especies';
    protected $primaryKey = 'esp_id';

    protected $fillable = [
        'esp_gene_id',
        'esp_nombre_cientifico',
        'esp_nombre_comun',
        'esp_descripcion',
        'esp_estado_valid',
        'created_at',
        'updated_at',
    ];

    public function genero()
    {
        return $this->belongsTo(Genero::class, 'esp_gene_id', 'gene_id');
    }

    public function imagenes()
    {
        return $this->hasMany(Imagen::class, 'img_esp_id', 'esp_id');
    }

    public function ubicaciones()
    {
        return $this->hasMany(Ubicacion::class, 'ubi_esp_id', 'esp_id');
    }

    public function registros()
    {
        return $this->hasMany(Registro::class, 'esp_id', 'esp_id');
    }
}
