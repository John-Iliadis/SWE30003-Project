<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'order_date',
        'customer_id',
        'customer_details_id',
        'card_id',
    ];
}
