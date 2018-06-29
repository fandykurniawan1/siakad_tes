<?php

namespace App\Models\UserManagement;

use Uuid;
use Illuminate\Database\Eloquent\Builder;
use Laratrust\LaratrustPermission;

class Permission extends LaratrustPermission
{
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::generate()->string;
        });

        static::addGlobalScope('scope', function(Builder $builder) {
            $builder->where('permissions.scope', 'Admin Backend');
        });
    }
}
