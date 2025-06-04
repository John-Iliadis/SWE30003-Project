<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderline extends Model
{
    protected $table = 'orderline';
    protected $primaryKey = 'orderline_id';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
