<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';

    protected $fillable = [
        'unidad_id', 'nombre', 'direccion', 'latitud', 'longitud', 
        'tamano_hectareas', 'fecha_establecimiento', 'gerente'
    ];

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function especializaciones()
    {
        return $this->belongsToMany(Especializacion::class, 'area_especializacion');
    }

    public function especies()
    {
        return $this->belongsToMany(Especie::class, 'area_especie');
    }

    public function naves()
    {
        return $this->hasMany(Nave::class);
    }
}
