<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id_animal',
        'id_oreja',
        'especie_id',
        'raza_id',
        'sexo',
        'fecha_nacimiento',
        'padre_id',
        'madre_id',
        'estado',
        'fase_reproductiva',
        'peso_nacimiento',
        'notas'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function especie()
    {
        return $this->belongsTo(Especie::class);
    }

    public function raza()
    {
        return $this->belongsTo(Raza::class);
    }

    // Pedigree
    public function padre()
    {
        return $this->belongsTo(Animal::class, 'padre_id');
    }

    public function madre()
    {
        return $this->belongsTo(Animal::class, 'madre_id');
    }

    public function hijos()
    {
        return $this->hasMany(Animal::class, 'padre_id')->orWhere('madre_id', $this->id);
    }
}
