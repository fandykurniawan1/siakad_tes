<?php

namespace App\Models\Master;

use Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $table = 'master_product_categories';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::generate()->string;
        });
    }

    public function getIsParentAttribute()
    {
        return ($this->parent_id) ? false : true;
    }
}
