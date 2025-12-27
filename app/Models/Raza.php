<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raza extends Model
{
    protected $fillable = ['especie_id', 'nombre', 'descripcion'];

    public function especie()
    {
        return $this->belongsTo(Especie::class);
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
}
