<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    protected $fillable = ['nombre', 'descripcion'];

    public function razas()
    {
        return $this->hasMany(Raza::class);
    }

    public function granjas()
    {
        return $this->belongsToMany(Granja::class, 'especie_granja');
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
}
