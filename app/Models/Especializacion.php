<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especializacion extends Model
{
    protected $table = 'especializacions';
    protected $fillable = ['nombre'];

    public function areas()
    {
        return $this->belongsToMany(Area::class, 'area_especializacion');
    }
}
