<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderline extends Model
{
    protected $table = 'orderline';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
    ];
}
