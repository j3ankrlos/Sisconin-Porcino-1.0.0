<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    use HasFactory;

    protected $table = 'secciones';

    protected $fillable = ['nave_id', 'nombre'];

    public function nave()
    {
        return $this->belongsTo(Nave::class);
    }
}
