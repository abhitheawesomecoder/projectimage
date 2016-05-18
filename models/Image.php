<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Config;

/**
 * Class Image
 * @author Netaviva Team <>
 */
class Image extends Model
{
    public $table = 'images';

    protected $timestamp = true;

    protected $fillable = ['name', 'path', 'size', 'width', 'height', 'user_id'];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return url(Config::get('imgshop.upload_paths.image') . '/' . $this->path);
    }

    public function tags()
    {
        return $this->hasMany('App\Models\Tag', 'image_id', 'id');
    }

    public function tags_icons()
    {
        return $this->hasMany('App\Models\TagsIcons');
    }

    public function product($product_id)
    {
        return $this->hasMany('App\Models\Product', 'product_id', $product_id);
    }
}
