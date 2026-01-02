<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    use SoftDeletes, \App\Traits\Auditable;

    protected $fillable = [
        'id_animal',
        'id_oreja',
        'especie_id',
        'raza_id',
        'seccion_id',
        'corral',
        'sexo',
        'fecha_nacimiento',
        'padre_id',
        'madre_id',
        'lote',
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

    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }

    /**
     * Obtiene la fecha de nacimiento en formato PIC (Vuelta-Pic).
     */
    public function getFechaPicAttribute()
    {
        return \App\Helpers\PicDateHelper::format($this->fecha_nacimiento);
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

    public function eventos()
    {
        return $this->hasMany(AnimalEvento::class)->orderBy('fecha', 'desc')->orderBy('created_at', 'desc');
    }
}
