<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::log('created', $model, [], $model->toArray());
        });

        static::updated(function ($model) {
            self::log('updated', $model, $model->getOriginal(), $model->getChanges());
        });

        static::deleted(function ($model) {
            self::log('deleted', $model, $model->toArray(), []);
        });
    }

    protected static function log($action, $model, $old, $new)
    {
        try {
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => $action,
                'model_type' => class_basename($model),
                'model_id'   => $model->id,
                'old_values' => $old ?: null,
                'new_values' => $new ?: null,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {}
    }
}