<?php

namespace App\Models;

use Uuid;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::generate()->string;
        });
    }

    public function entity()
    {
        return $this->morphTo();
    }
}
