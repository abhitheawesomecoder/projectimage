<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * @author Nisgeo Team <>
 */
class Payment extends Model
{
    public $table = 'payments';

    protected $fillable = [
        'user_id',
        'BuyerName',
        'BuyerEmail',
        'PaymentID',
        'ItemAmount',
        'ItemCurrency'
    ];

}
