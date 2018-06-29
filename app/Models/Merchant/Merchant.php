<?php

namespace App\Models\Merchant;

use Uuid;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchant extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::generate()->string;
        });

        static::addGlobalScope('verified', function(Builder $builder) {
            $builder->where('merchants.status', '!=', 'Not Verified');
        });
    }

    public function getPhotoAttribute()
    {
        return optional($this->photo()->first())->url;
    }

    public function photo()
    {
        return $this->images()->whereType('photo');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'entity');
    }
}
