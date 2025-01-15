<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    protected $table = 'tax_familias';
    protected $primaryKey = 'fam_id';

    protected $fillable = [
        'fam_reino_id',
        'fam_nombre'
    ];

    public function reino()
    {
        return $this->belongsTo(Reino::class, 'fam_reino_id', 'reino_id');
    }

    public function generos()
    {
        return $this->hasMany(Genero::class, 'gene_fam_id', 'fam_id');
    }
}
