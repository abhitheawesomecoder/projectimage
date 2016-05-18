<?php

namespace App\Models;

use App\Lib\CurrencyService;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Image
 * @author Netaviva Team <>
 */
class Product extends Model
{
    public $table = 'products';

    protected $timestamp = true;

    protected $fillable = [
        'type',
        'title',
        'description',
        'price',
        'url',
        'currency',
        'user_id',
        'youtube',
        'paypal',
        'button',
        'image'
    ];

    protected $appends = ['price_formatted'];

    /**
     * @return string
     */
    public function getPriceFormattedAttribute()
    {
        return implode(' ', [CurrencyService::getSymbol($this->currency), $this->price]);
    }
}
