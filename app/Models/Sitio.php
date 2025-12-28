<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sitio extends Model
{
    protected $fillable = ['granja_id', 'nombre'];

    public function granja(): BelongsTo
    {
        return $this->belongsTo(Granja::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
