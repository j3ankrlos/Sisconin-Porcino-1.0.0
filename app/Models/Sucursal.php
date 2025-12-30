<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sucursal extends Model
{
    protected $table = 'sucursales';

    protected $fillable = ['empresa_id', 'nombre', 'direccion'];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function unidades(): HasMany
    {
        return $this->hasMany(Unidad::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
