<?php

namespace App\Models\UserManagement;

use Uuid;
use Illuminate\Database\Eloquent\Builder;
use Laratrust\LaratrustRole;

class Role extends LaratrustRole
{
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::generate()->string;
        });

        static::addGlobalScope('platform', function(Builder $builder) {
            $builder->where('roles.platform', 'Admin');
        });
    }
}
