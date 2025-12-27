<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Granja extends Model
{
    protected $fillable = [
        'empresa_id', 'nombre', 'direccion', 'latitud', 'longitud', 
        'tamano_hectareas', 'fecha_establecimiento', 'gerente'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function especializaciones()
    {
        return $this->belongsToMany(Especializacion::class, 'granja_especializacion');
    }

    public function especies()
    {
        return $this->belongsToMany(Especie::class, 'especie_granja');
    }

    public function naves()
    {
        return $this->hasMany(Nave::class);
    }
}
