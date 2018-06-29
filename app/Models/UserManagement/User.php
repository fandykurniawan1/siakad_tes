<?php

namespace App\Models\UserManagement;

use Uuid;
use App\Models\Merchant\Merchant;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, LaratrustUserTrait, SoftDeletes;

    public $incrementing = false;
    protected $hidden = ['password', 'remember_token'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('platform', function(Builder $builder) {
            $builder->where('users.platform', 'Admin');
        });

        static::addGlobalScope('active', function(Builder $builder) {
            $builder->where('users.active', 1);
        });

        static::creating(function ($model) {
            $model->id = Uuid::generate()->string;
        });
    }

    public function getRoleAttribute()
    {
        return $this->roles->first();
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
