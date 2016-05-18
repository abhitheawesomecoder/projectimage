<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $table = 'tags';

    public $timestamps = false;

    protected $fillable = ['color', 'image_id', 'product_id', 'pos_x', 'pos_y', 'user_id'];

    public function image()
    {
        return $this->belongsTo('App\Models\Image', 'image_id', 'id');
    }

    public function tags_icons()
    {
        return $this->hasMany('App\Models\TagsIcons');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}
