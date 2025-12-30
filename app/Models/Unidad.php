<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unidad extends Model
{
    protected $table = 'unidades';

    protected $fillable = ['sucursal_id', 'nombre'];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
