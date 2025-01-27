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

    // Relaciones existentes
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

    public function revisiones()
    {
        return $this->hasMany(Revision::class, 'esp_id', 'esp_id');
    }

    // Método para obtener el nombre del estado
    public function getEstadoNombre()
    {
        switch($this->esp_estado_valid) {
            case 1:
                return 'Pendiente';
            case 2:
                return 'Aprobado';
            case 3:
                return 'Rechazado';
            default:
                return 'Pendiente';
        }
    }

    // Método para obtener el color de la clase CSS según el estado
    public function getEstadoClase()
    {
        switch($this->esp_estado_valid) {
            case 1:
                return 'estado-pendiente';
            case 2:
                return 'estado-aprobado';
            case 3:
                return 'estado-rechazado';
            default:
                return 'estado-pendiente';
        }
    }

    // Método para verificar si la especie está pendiente
    public function estaPendiente()
    {
        return $this->esp_estado_valid == 1;
    }

    // Método para verificar si la especie está aprobada
    public function estaAprobada()
    {
        return $this->esp_estado_valid == 2;
    }

    // Método para verificar si la especie está rechazada
    public function estaRechazada()
    {
        return $this->esp_estado_valid == 3;
    }
}