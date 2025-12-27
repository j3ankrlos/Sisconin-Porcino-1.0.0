<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especializacion extends Model
{
    protected $table = 'especializacions';
    protected $fillable = ['nombre'];

    public function granjas()
    {
        return $this->belongsToMany(Granja::class, 'granja_especializacion');
    }
}
