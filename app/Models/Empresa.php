<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['nombre', 'direccion', 'telefono', 'email', 'nit', 'fecha_fundacion'];

    public function granjas()
    {
        return $this->hasMany(Granja::class);
    }
}
