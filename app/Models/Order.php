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

    public function customerDetails()
    {
        return $this->belongsTo(CustomerDetails::class, 'customer_details_id', 'customer_details_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function creditCard()
    {
        return $this->belongsTo(CreditCard::class, 'card_id', 'card_id');
    }
}
