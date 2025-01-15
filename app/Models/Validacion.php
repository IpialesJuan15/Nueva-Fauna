<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validacion extends Model
{
    protected $table = 'tax_validaciones';
    protected $primaryKey = 'valid_id';

    protected $fillable = [
        'valid_regis_id',
        'valid_user_id',
        'valid_fecha',
        'valid_comentarios',
        'created_at',
        'updated_at',
    ];

    public function registro()
    {
        return $this->belongsTo(Registro::class, 'valid_regis_id', 'regis_id');
    }
}
