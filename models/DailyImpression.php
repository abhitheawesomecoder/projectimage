<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyImpression extends Model
{
    public $table = 'daily_impressions';

    protected $fillable = ['day', 'user_id', 'count'];

    public $timestamps = false;
}
