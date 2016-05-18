<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagsIcons extends Model
{
    public $table = 'tags_icons';

    protected $fillable = ['image', 'premium', 'user_id'];
}
