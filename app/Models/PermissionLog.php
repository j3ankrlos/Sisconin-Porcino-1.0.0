<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionLog extends Model
{
    protected $fillable = [
        'causer_id',
        'user_id',
        'action',
        'permissions_after'
    ];

    protected $casts = [
        'permissions_after' => 'array'
    ];

    public function causer()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
