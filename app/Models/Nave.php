<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nave extends Model
{
    use HasFactory;

    protected $fillable = ['area_id', 'nombre'];
    
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function secciones()
    {
        return $this->hasMany(Seccion::class);
    }
}
