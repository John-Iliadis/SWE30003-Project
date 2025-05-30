<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    protected $table = 'credit_cards';
    protected $primaryKey = 'card_id';

    // You might need to add this if it's not already there:
    // use App\Models\User;

    protected $fillable = [
        'cardholder_name',
        'card_number',
        'expiration_month',
        'expiration_year',
        'user_id', // Add user_id to fillable if you plan to mass assign it
    ];

    public function user() { // Renamed from customer()
        return $this->belongsTo(User::class); // Changed to belongsTo(User::class)
    }

    // You can comment out or remove the old customer() method:
    /*
    public function customer() {
        return $this->hasOne(Customer::class, 'card_id');
    }
    */
}
