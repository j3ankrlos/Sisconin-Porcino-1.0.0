<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalEvento extends Model
{
    protected $fillable = [
        'animal_id',
        'user_id',
        'tipo',
        'suceso',
        'detalle',
        'seccion_id',
        'corral',
        'fecha',
        'metadata'
    ];

    protected $casts = [
        'fecha' => 'date',
        'metadata' => 'array'
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }
}
