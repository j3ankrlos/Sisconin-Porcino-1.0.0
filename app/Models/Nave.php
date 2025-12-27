<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nave extends Model
{
    use HasFactory;

    protected $fillable = ['granja_id', 'nombre'];

    public function granja()
    {
        return $this->belongsTo(Granja::class);
    }

    public function secciones()
    {
        return $this->hasMany(Seccion::class);
    }
}
