<?php

namespace App\Models;

use Uuid;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::generate()->string;
        });
    }
}
