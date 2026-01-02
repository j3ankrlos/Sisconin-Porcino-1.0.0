<?php

namespace App\Traits;

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::audit('created', $model);
        });

        static::updated(function ($model) {
            self::audit('updated', $model);
        });

        static::deleted(function ($model) {
            self::audit('deleted', $model);
        });
    }

    protected static function audit($event, $model)
    {
        $oldValues = [];
        $newValues = [];

        if ($event === 'updated') {
            $oldValues = $model->getOriginal();
            $newValues = $model->getChanges();
        } elseif ($event === 'created') {
            $newValues = $model->getAttributes();
        } elseif ($event === 'deleted') {
            $oldValues = $model->getAttributes();
        }

        // Remove hidden attributes
        $hidden = $model->getHidden();
        foreach ($hidden as $attribute) {
            unset($oldValues[$attribute]);
            unset($newValues[$attribute]);
        }

        Audit::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($newValues),
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
