<?php

namespace App\Models;

use Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::generate()->string;
        });

        static::addGlobalScope('platform', function(Builder $builder) {
            $builder->where('preferences.platform', 'Admin');
        });
    }

    public function scopeOf($query, $key)
    {
        return $query->where('key', $key);
    }

    public static function valueOf($key)
    {
        $preference = static::of($key)->first();

        return $preference ? $preference->value : null;
    }

    public function scopeUpdateValueOf($query, $key, $newValue)
    {
        return $query->where('key', $key)->update(['value' => $newValue]);
    }
}
